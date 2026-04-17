<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Quiniela;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;
use App\Models\Jugador;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('jugador')->orderBy('fecha_pago', 'desc')->get();
        return view('pagos.index', compact('pagos'));
    }

    public function marcarPagado($id)
    {
        try {
            $pago = Pago::with('jugador')->findOrFail($id);

            // Cambiar estado
            $pago->estado = 'pagado';
            $pago->save();

            $jugador = $pago->jugador;

            // Traer las quinielas del jugador en esa jornada
            $quinielas = \App\Models\Quiniela::where('jugador_id', $jugador->id)
                ->where('numero', $pago->numero)
                ->with('respuestas') // si tienes relación con las opciones elegidas
                ->get();

            // Generar PDF con jugador, pago y quinielas
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante', compact('jugador', 'pago', 'quinielas'));
            $pdfPath = "comprobantes/comprobante_{$jugador->id}_{$pago->id}.pdf";

            \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

            $pago->update([
                'comprobante_pdf' => $pdfPath,
            ]);

            return redirect()->route('pagos.index')->with('success', '✅ Pago marcado como pagado y comprobante generado.');
        } catch (\Exception $e) {
            return redirect()
                ->route('pagos.index')
                ->with('error', '❌ Error al marcar como pagado: ' . $e->getMessage());
        }
    }


    public function comprobante($id)
{
    $pago = Pago::with('jugador')->findOrFail($id);

    $jugador = $pago->jugador;
    $quinielas = \App\Models\Quiniela::where('jugador_id', $jugador->id)
        ->where('numero', $pago->numero)
        ->with('respuestas')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante', compact('jugador', 'pago', 'quinielas'));
    return $pdf->download("comprobante_{$jugador->id}_{$pago->id}.pdf");
}


    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->comprobante_pdf && Storage::disk('public')->exists($pago->comprobante_pdf)) {
            Storage::disk('public')->delete($pago->comprobante_pdf);
        }

        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago y comprobante eliminados correctamente.');
    }
}
