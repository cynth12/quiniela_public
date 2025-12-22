<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Jugador;
use App\Models\Quiniela;
use App\Models\Respuestas;
use Illuminate\Http\Request;

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

        $partidos = $jornadaModelo->partidos->map(function ($p) {
            return [
                'local' => $p->local,
                'visitante' => $p->visitante,
            ];
        });

        return view('quiniela.public', compact('jornada', 'partidos'));
    }

    // Guardar quiniela pública
    public function store(Request $request)
{
    $quinielas = $request->input('quinielas');

    if (!is_array($quinielas) || count($quinielas) === 0) {
        return response()->json([
            'success' => false,
            'error' => 'No se recibieron quinielas válidas.'
        ]);
    }

    try {
        $jugador = null;

        foreach ($quinielas as $q) {
            // Crear o recuperar jugador
            $jugador = Jugador::firstOrCreate([
                'nombre' => $q['nombre'],
                'telefono' => $q['telefono'],
            ]);

            // Crear quiniela asociada
            $quiniela = Quiniela::create([
                'jugador_id' => $jugador->id,
                'numero' => $q['numero'], // jornada
                'numero_quiniela' => uniqid(),
                'pagada' => false,
            ]);

            // Guardar respuestas
            foreach ($q['resultados'] as $partido_numero => $respuesta) {
                Respuestas::create([
                    'quiniela_id' => $quiniela->id,
                    'partido_numero' => $partido_numero + 1,
                    'respuesta' => $respuesta,
                ]);
            }
        }

        // Devolver el jugador_id para redirigir al pago
        return response()->json([
            'success' => true,
            'jugador_id' => $jugador->id,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
}