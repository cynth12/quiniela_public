<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Respuestas;
use Illuminate\Http\Request;
use App\Models\Quiniela;
use App\Models\Pago;
use Barryvdh\DomPDF\Facade\Pdf;

class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $jugadores = Jugador::with('quinielas')->get();
        return view('jugadores.index', compact('jugadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Jugador $jugador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jugador $jugador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jugador $jugador)
    {
        //
    }

    public function marcarPagado($id)
    {
        try {
            $jugador = Jugador::with('quinielas')->findOrFail($id);

            // Cambiar estado del jugador
            $jugador->pagada = 1;
            $jugador->save();

            // Tomar la quiniela del jugador
            $quiniela = $jugador->quinielas->first();

            if ($quiniela) {
                // Buscar el pago que coincide con jugador_id y numero de la quiniela
                $pago = Pago::where('jugador_id', $jugador->id)->where('numero', $quiniela->numero)->first();

                if ($pago) {
                    // Generar PDF
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.comprobante', compact('jugador', 'pago'));
                    $pdfPath = "comprobantes/comprobante_{$jugador->id}_{$quiniela->numero}.pdf";
                    $pdf->save(storage_path("app/public/{$pdfPath}"));

                    // Actualizar pago
                    $pago->update([
                        'estado' => 'pagado',
                        'comprobante_pdf' => $pdfPath,
                    ]);
                }
            }

            return redirect()->route('jugadores.index')->with('success', '✅ Jugador marcado como pagado y comprobante generado.');
        } catch (\Exception $e) {
            return redirect()
                ->route('jugadores.index')
                ->with('error', '❌ Error al marcar como pagado: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jugador $jugador)
    {
        foreach ($jugador->quinielas as $q) {
            $q->respuestas()->delete();
            $q->delete();
        }
        $jugador->delete();

        return redirect()->route('jugadores.index')->with('success', 'Jugador y sus quinielas eliminados correctamente.');
    }

    public function archivarTodos()
    {
        // 🔥 Marcar todos los jugadores de la jornada actual como archivados
        Jugador::query()->update(['archivado' => true]);
        Quiniela::query()->update(['archivado' => true]);
        Pago::query()->update(['archivado' => true]);

        return back()->with('success', 'Todos los registros fueron archivados. El panel está limpio para la nueva jornada.');
    }

    public function archivo()
    {
        $jugadoresArchivados = Jugador::where('archivado', true)
            ->with([
                'quinielas' => function ($q) {
                    $q->where('archivado', true);
                },
                'pagos' => function ($p) {
                    $p->where('archivado', true);
                },
            ])
            ->get();

        return view('archivo.index', compact('jugadoresArchivados'));
    }

    public function borrarTodosJugadores()
    {
        // Eliminar todos los jugadores junto con sus quinielas y pagos
        Jugador::query()->delete();
        Quiniela::query()->delete();
        Pago::query()->delete();

        return back()->with('success', 'Todos los jugadores, quinielas y pagos fueron eliminados.');
    }
}
