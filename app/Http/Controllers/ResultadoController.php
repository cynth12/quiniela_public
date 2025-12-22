<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jornada;
use App\Models\Resultado;
use App\Models\Jugador;

class ResultadoController extends Controller
{
    // Muestra todas las jornadas cerradas con sus resultados
    public function index()
{
    // Jornadas cerradas
    $jornadas = Jornada::where('cerrada', true)
        ->orderBy('numero')
        ->with('partidos')
        ->get();

    // Resultados oficiales agrupados por jornada
    $resultadosOficiales = Resultado::whereNotNull('resultado_oficial')
        ->get()
        ->groupBy('numero');

    return view('resultados.index', compact('jornadas', 'resultadosOficiales'));
}

    // Muestra los partidos y resultados oficiales de una jornada específica
    public function show($id)
    {
        $jornada = Jornada::with('partidos')->findOrFail($id);

        $resultados = Resultado::where('numero', $jornada->numero)->get()
            ->keyBy('partido_numero');

        return view('resultados.index', compact('jornada', 'resultados'));
    }

    // Guarda o actualiza los resultados oficiales de una jornada
    public function guardarResultados(Request $request, $numero)
{
    foreach ($request->resultados as $partido_numero => $resultado) {
        Resultado::updateOrCreate(
            ['numero' => $numero, 'partido_numero' => $partido_numero],
            ['resultado_oficial' => $resultado]
        );
    }

    return back()->with('success', 'Resultados oficiales guardados correctamente.');
}

    // Cierra la jornada si todos los resultados oficiales están completos
    public function cerrarJornada($numero)
    {
        $jornada = Jornada::with('partidos')->where('numero', $numero)->firstOrFail();

        foreach ($jornada->partidos as $partido) {
            $resultado = Resultado::where('numero', $numero)
                ->where('partido_numero', $partido->partido_numero)
                ->first();

            if (!$resultado || !$resultado->resultado_oficial) {
                return back()->with('error', 'Completa todos los resultados oficiales antes de cerrar la jornada.');
            }
        }

        $jornada->cerrada = true;
        $jornada->save();

        return redirect()->route('resultados.index')->with('success', 'Jornada cerrada correctamente.');
    }
}




