<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quiniela;
use App\Models\Pago;
use App\Models\Jugador;
use Barryvdh\DomPDF\Facade\Pdf;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('jugador')
            ->orderBy('fecha_pago', 'desc')
            ->get();

        return view('pagos.index', compact('pagos'));
    }

    public function marcarPagado($id)
    {
        try {
            $pago = Pago::with('jugador')->findOrFail($id);

            $pago->estado = 'pagado';
            $pago->save();

            $jugador = $pago->jugador;

            $quinielas = Quiniela::where('jugador_id', $jugador->id)
                ->where('numero', $pago->numero)
                ->with('respuestas')
                ->get();

            $pdf = Pdf::loadView('pdf.comprobante', compact('jugador', 'pago', 'quinielas'));

            $pdfPath = "comprobantes/comprobante_{$jugador->id}_{$pago->id}.pdf";

            Storage::disk('public')->put($pdfPath, $pdf->output());

            $pago->update([
                'comprobante_pdf' => $pdfPath,
            ]);

            return redirect()
                ->route('pagos.index')
                ->with('success', '✅ Pago marcado como pagado y comprobante generado.');

        } catch (\Exception $e) {
            return redirect()
                ->route('pagos.index')
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    public function comprobante($id)
    {
        $pago = Pago::with('jugador')->findOrFail($id);

        $jugador = $pago->jugador;

        $quinielas = Quiniela::where('jugador_id', $jugador->id)
            ->where('numero', $pago->numero)
            ->with('respuestas')
            ->get();

        $pdf = Pdf::loadView('pdf.comprobante', compact('jugador', 'pago', 'quinielas'));

        return $pdf->download("comprobante_{$jugador->id}_{$pago->id}.pdf");
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->comprobante_pdf && Storage::disk('public')->exists($pago->comprobante_pdf)) {
            Storage::disk('public')->delete($pago->comprobante_pdf);
        }

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | 💳 GENERAR LINK MERCADO PAGO (WHATSAPP FLOW)
    |--------------------------------------------------------------------------
    */

    public function generarLink($token)
{
    $jugador = Jugador::where('token_pago', $token)->firstOrFail();

    $quinielas = Quiniela::where('jugador_id', $jugador->id)
        ->where('numero', $jugador->numero_actual)
        ->get();

    if ($quinielas->isEmpty()) {
        return response()->json([
            'error' => 'No hay quinielas para pagar'
        ]);
    }

    $total = $quinielas->count() * 10;

    MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

    $client = new PreferenceClient();

    $preference = $client->create([
        "items" => [
            [
                "title" => "Quiniela ZAS - " . $jugador->nombre,
                "quantity" => 1,
                "unit_price" => $total,
                "currency_id" => "MXN"
            ]
        ],
        "external_reference" => $token
    ]);

    return response()->json([
        'link_pago' => $preference->init_point,
        'total' => $total,
        'nombre' => $jugador->nombre
    ]);
}
}