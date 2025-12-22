<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quiniela;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;
use App\Models\Jugador;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payment;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('jugador')->orderBy('fecha_pago', 'desc')->get();
        return view('pagos.index', compact('pagos'));
    }

    // Generar preferencia de pago en Mercado Pago
    public function generarPago($jugadorId)
    {
        $jugador = Jugador::with('quinielas')->findOrFail($jugadorId);

        SDK::setAccessToken(env('MERCADOPAGO_TOKEN'));

        $preference = new Preference();

        $item = new Item();
        $item->title = "Quinielas Jornada {$jugador->quinielas->last()->numero}";
        $item->quantity = $jugador->quinielas->count();
        $item->unit_price = 10;
        $preference->items = [$item];

        $preference->external_reference = "ID:{$jugador->id}-Tel:{$jugador->telefono}";
        $preference->save();

        return redirect($preference->init_point);
    }

    // Webhook de Mercado Pago (confirmación automática)
    public function webhook(Request $request)
    {
        SDK::setAccessToken(env('MERCADOPAGO_TOKEN'));
        $paymentId = $request->input('data.id');
        $payment = Payment::find_by_id($paymentId);

        if ($payment && $payment->status == 'approved') {
            preg_match('/ID:(\d+)-Tel:(\d+)/', $payment->external_reference, $matches);
            $jugadorId = $matches[1];

            $jugador = Jugador::findOrFail($jugadorId);
            $quinielas = Quiniela::with('respuestas')->where('jugador_id', $jugadorId)->get();

            $monto = $quinielas->count() * 10;

            $pago = Pago::create([
                'jugador_id' => $jugador->id,
                'numero' => $quinielas->last()->numero,
                'monto' => $monto,
                'fecha_pago' => now(),
            ]);

            // Generar PDF
            $pdf = Pdf::loadView('pdf.comprobante', compact('quinielas', 'pago'));
            $filename = 'comprobante_pago_' . $pago->id . '.pdf';
            $pdf->save(storage_path('app/public/' . $filename));
            $pago->update(['comprobante_pdf' => $filename]);

            // Enviar comprobante por WhatsApp
            $this->enviarComprobanteWhatsapp($jugador, $pago);
        }

        return response()->json(['status' => 'ok']);
    }

    public function enviarComprobanteWhatsapp($jugador, $pago)
    {
        $pdfUrl = asset('storage/' . $pago->comprobante_pdf);

        Http::withToken(env('WHATSAPP_TOKEN'))->post('https://graph.facebook.com/v17.0/' . env('WHATSAPP_PHONE_ID') . '/messages', [
            'messaging_product' => 'whatsapp',
            'to' => $jugador->telefono,
            'type' => 'document',
            'document' => [
                'link' => $pdfUrl,
                'caption' => "Comprobante de pago - Jugador ID: {$jugador->id}",
            ],
        ]);
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        // Eliminar el PDF si existe
        if ($pago->comprobante_pdf && Storage::disk('public')->exists($pago->comprobante_pdf)) {
            Storage::disk('public')->delete($pago->comprobante_pdf);
        }

        // Eliminar el registro de la base de datos
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago y comprobante eliminados correctamente.');
    }
}
