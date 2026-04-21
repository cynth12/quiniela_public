<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Jornada;
use App\Models\Jugador;
use App\Models\Quiniela;
use App\Models\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuinielaPublicController extends Controller
{


    // Mostrar jornada y partidos por número
    public function jornadaPorNumero($numero, Request $request)
    {
        $jornadaModelo = Jornada::with('partidos')->where('numero', $numero)->firstOrFail();

        // 🚫 Validación: si la jornada está cerrada, no se abre el link
    if ($jornadaModelo->cerrada) {
        abort(403, '⚠ Esta jornada ya está cerrada, no se puede acceder.');
    }

        $jornada = [
            'id' => $jornadaModelo->id,
            'numero' => $jornadaModelo->numero,
            'fecha' => $jornadaModelo->fecha,
            'premio' => $jornadaModelo->premio,
        ];

        $partidos = $jornadaModelo->partidos->map(function ($p, $idx) {
            return [
                'partido_numero' => $idx + 1,
                'local' => $p->local,
                'visitante' => $p->visitante,
            ];
        });

        $jugador = null;
        if ($request->has('jugador_id')) {
            $jugador = Jugador::with('quinielas')->find($request->jugador_id);
        }

        return view('quiniela.public', compact('jornada', 'partidos', 'jugador'));
    }

    // Guardar quinielas públicas (JSON desde fetch)
  public function store(Request $request)
{



    $quinielas = $request->input('quinielas');

    if (!is_array($quinielas) || count($quinielas) === 0) {
        return response()->json([
            'success' => false,
            'error' => 'No se recibieron quinielas válidas.',
        ], 422);
    }

    try {
        DB::beginTransaction();

        $jugador = null;
        $totalGuardadas = 0;
        $jornadaNumero = null;

        foreach ($quinielas as $q) {
            if (empty($q['nombre']) || empty($q['telefono']) || !isset($q['numero']) || !isset($q['resultados'])) {
                throw new \Exception('Estructura de quiniela inválida.');
            }

            $jugador = Jugador::firstOrCreate([
                'nombre' => $q['nombre'],
                'telefono' => $q['telefono'],
            ]);

            $quiniela = Quiniela::create([
                'jugador_id' => $jugador->id,
                'numero' => $q['numero'],
                'numero_quiniela' => uniqid(),
                'estado' => 'pendiente',
            ]);

            foreach ($q['resultados'] as $partido_numero => $respuesta) {
                Respuestas::create([
                    'quiniela_id' => $quiniela->id,
                    'partido_numero' => $partido_numero + 1,
                    'respuesta' => $respuesta,
                ]);
            }

            $totalGuardadas++;
            $jornadaNumero = $q['numero']; // guardamos la jornada
        }

        // 👉 Crear un solo pago acumulado
        $montoTotal = $totalGuardadas * 10; // cada quiniela cuesta $10

        Pago::updateOrCreate(
            [
                'jugador_id' => $jugador->id,
                'numero' => $jornadaNumero,
            ],
            [
                'monto' => $montoTotal,
                'fecha_pago' => now(),
                'estado' => 'pendiente',
            ]
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'jugador_id' => $jugador->id,
            'cantidad' => $totalGuardadas,
            'total' => $montoTotal,
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}


    
}
