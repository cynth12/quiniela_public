<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Jugador;
use App\Models\Quiniela;
use App\Models\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuinielaPublicController extends Controller
{
    // Mostrar jornada y partidos por número
    public function jornadaPorNumero($numero)
    {
        $jornadaModelo = Jornada::with('partidos')->where('numero', $numero)->firstOrFail();

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

        return view('quiniela.public', compact('jornada', 'partidos'));
    }

    // Guardar quinielas públicas (JSON desde fetch)
    public function store(Request $request)
    {
        $quinielas = $request->input('quinielas');

        if (!is_array($quinielas) || count($quinielas) === 0) {
            return response()->json([
                'success' => false,
                'error' => 'No se recibieron quinielas válidas.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $jugador = null;
            $totalGuardadas = 0;

            foreach ($quinielas as $q) {
                // Validación mínima
                if (
                    empty($q['nombre']) ||
                    empty($q['telefono']) ||
                    !isset($q['numero']) ||
                    !isset($q['resultados']) ||
                    !is_array($q['resultados']) ||
                    count($q['resultados']) === 0
                ) {
                    throw new \Exception('Estructura de quiniela inválida.');
                }

                // Crear o recuperar jugador (pagada está en jugadores, no en quinielas)
                $jugador = Jugador::firstOrCreate([
                    'nombre' => $q['nombre'],
                    'telefono' => $q['telefono'],
                ]);

                // Crear quiniela asociada (sin 'pagada', porque no existe en esta tabla)
                $quiniela = Quiniela::create([
                    'jugador_id' => $jugador->id,
                    'numero' => $q['numero'], // jornada
                    'numero_quiniela' => uniqid(),
                ]);

                // Guardar respuestas
                foreach ($q['resultados'] as $partido_numero => $respuesta) {
                    Respuestas::create([
                        'quiniela_id' => $quiniela->id,
                        'partido_numero' => $partido_numero + 1,
                        'respuesta' => $respuesta,
                    ]);
                }

                $totalGuardadas++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "✅ Se guardaron {$totalGuardadas} quiniela(s) correctamente.",
                'jugador_id' => $jugador->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
