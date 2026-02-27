<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quiniela;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;
use App\Models\Jugador;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Http;

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

        $total = $jugador->quinielas->count() * 10;

        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_TOKEN'));

        $client = new PreferenceClient();
        $ultimaQuiniela = $jugador->quinielas->last();
        $titulo = $ultimaQuiniela ? "Quinielas Jornada {$ultimaQuiniela->numero}" : 'Quinielas';

        $preference = $client->create([
            'items' => [
                [
                    'title' => 'Pago Quiniela ' . $jugador->nombre,
                    'quantity' => 1,
                    'currency_id' => 'MXN',
                    'unit_price' => $total,
                ],
            ],
            'external_reference' => "ID:{$jugador->id}-Tel:{$jugador->telefono}",
            'back_urls' => [
                'success' => route('pagos.success', ['jugadorId' => $jugador->id]),
                'failure' => route('pagos.failure', ['jugadorId' => $jugador->id]),
                'pending' => route('pagos.pending', ['jugadorId' => $jugador->id]),
            ],
            'auto_return' => 'approved',
        ]);

        return redirect()->away($preference->init_point);
    }

    // Webhook de Mercado Pago (confirmación automática)
    public function webhook(Request $request)
    {
        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_TOKEN'));

        $paymentId = $request->input('data.id');
        $client = new PaymentClient();
        $payment = $client->get($paymentId);

        if ($payment && $payment->status === 'approved') {
            preg_match('/ID:(\d+)-Tel:(\d+)/', $payment->external_reference, $matches);
            $jugadorId = $matches[1];

            $jugador = Jugador::findOrFail($jugadorId);

            // 🔑 Actualizar estado del jugador
            $jugador->pagada = 1;
            $jugador->save();

            $quinielas = Quiniela::with('respuestas')->where('jugador_id', $jugadorId)->get();
            $monto = $quinielas->count() * 10;

            $pago = Pago::create([
                'jugador_id' => $jugador->id,
                'numero' => optional($quinielas->last())->numero,
                'monto' => $monto,
                'fecha_pago' => now(),
            ]);

            // 🔑 Actualizar estado de las quinielas
            foreach ($quinielas as $quiniela) {
                $quiniela->estado = 'pagada';
                $quiniela->save();
            }

            $pdf = Pdf::loadView('pdf.comprobante', compact('quinielas', 'pago'));
            $filename = 'comprobante_pago_' . $pago->id . '.pdf';
            $pdf->save(storage_path('app/public/' . $filename));
            $pago->update(['comprobante_pdf' => $filename]);

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

    public function success(Request $request)
    {
        $jugadorId = $request->query('jugadorId');
        $pago = Pago::where('jugador_id', $jugadorId)->latest()->first();
        $quinielas = Quiniela::where('jugador_id', $jugadorId)->get();

        return view('pagos.success', compact('pago', 'quinielas'));
    }

    public function failure(Request $request)
    {
        $jugadorId = $request->query('jugadorId');
        $jugador = Jugador::find($jugadorId);
        $quinielas = Quiniela::where('jugador_id', $jugadorId)->get();

        return view('pagos.failure', compact('jugador', 'quinielas'));
    }

    public function pending(Request $request)
    {
        $jugadorId = $request->query('jugadorId');
        $jugador = Jugador::find($jugadorId);
        $quinielas = Quiniela::where('jugador_id', $jugadorId)->get();

        return view('pagos.pending', compact('jugador', 'quinielas'));
    }
}
