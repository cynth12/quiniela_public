<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jornada;
use App\Models\QuinielaPublica;
use App\Models\Pago;
use App\Models\Partido;

class EstadisticaController extends Controller
{
    public function cerrarJornada($id)
    {
        // 1. Buscar la jornada
        $jornada = Jornada::findOrFail($id);

        // 2. Calcular dinero generado
        $tokens = QuinielaPublica::where('jornada_id', $id)->pluck('session_token');
        $total = Pago::whereIn('session_token', $tokens)->sum('monto_total');
        $premio = $total * 0.70;
        $ganancia = $total * 0.30;

        // 3. Calcular aciertos por jugador
        $resultadosOficiales = Partido::where('jornada_id', $id)
            ->pluck('resultado_oficial')
            ->toArray();

        $quinielas = QuinielaPublica::where('jornada_id', $id)
            ->where('pagado', true)
            ->get();

        $aciertosPorJugador = [];

        foreach ($quinielas as $q) {
            $selecciones = json_decode($q->resultados_seleccionados);
            $aciertos = 0;

            foreach ($selecciones as $i => $respuesta) {
                if (isset($resultadosOficiales[$i]) && $respuesta === $resultadosOficiales[$i]) {
                    $aciertos++;
                }
            }

            // Guardar aciertos por jugador
            $aciertosPorJugador[$q->jugador_id] = $aciertos;
        }

        // 4. Retornar vista con resultados
        return view('estadisticas.resultados', compact(
            'jornada',
            'total',
            'premio',
            'ganancia',
            'aciertosPorJugador'
        ));
    }
}
