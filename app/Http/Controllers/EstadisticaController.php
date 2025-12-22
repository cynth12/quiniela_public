<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function cerrarJornada($id)
{
    $jornada = Jornada::findOrFail($id);

    // 1. Calcular dinero generado
    $tokens = QuinielaPublica::where('jornada_id', $id)->pluck('session_token');
    $total = Pago::whereIn('session_token', $tokens)->sum('monto_total');
    $premio = $total * 0.70;
    $ganancia = $total * 0.30;

    // 2. Calcular aciertos por jugador
    $resultadosOficiales = Partido::where('jornada_id', $id)->pluck('resultado_oficial')->toArray();
    $quinielas = QuinielaPublica::where('jornada_id', $id)->where('pagado', true)->get();

    $aciertosPorJugador = [];

    foreach ($quinielas as $q) {
        $selecciones = json_decode($q->resultados_seleccionados);
        $aciertos = 0;
        foreach ($selecciones as $i => $respuesta) {
            if (isset($resultadosOficiales[$i]) && $respuesta === $resultadosOficiales[$i]) {
                $aciertos++;
            }
        }
        $aciertosPorJugador[] = [
            'nombre' => $q->nombre_jugador,
            'telefono' => $q->telefono,
            'aciertos' => $aciertos,
        ];
    }

    // 3. Declarar ganadores (solo los que tienen 9 aciertos)
    $ganadores = collect($aciertosPorJugador)->where('aciertos', 9);
    $premioPorJugador = $ganadores->count() > 0 ? $premio / $ganadores->count() : 0;

    foreach ($ganadores as $g) {
        Ganador::create([
            'jornada_id' => $id,
            'nombre_jugador' => $g['nombre'],
            'telefono' => $g['telefono'],
            'aciertos' => $g['aciertos'],
            'lugar' => '1er',
        ]);
    }

    // 4. Guardar estadísticas
    Estadistica::updateOrCreate(
        ['jornada_id' => $id],
        [
            'total_dinero_generado' => $total,
            'premio_acumulado' => $premio,
            'ganancia_administrador' => $ganancia,
            'total_participantes' => $quinielas->count(),
            'promedio_aciertos' => round(collect($aciertosPorJugador)->avg('aciertos'), 2),
        ]
    );

    return back()->with('success', 'Jornada cerrada. Ganadores calculados y estadísticas actualizadas.');
}

public function verCierre($id)
{
    $jornada = Jornada::findOrFail($id);
    $estadistica = Estadistica::where('jornada_id', $id)->first();
    $ganadores = Ganador::where('jornada_id', $id)->get();

    return view('estadistica.cierre', compact('jornada', 'estadistica', 'ganadores'));
}


}
