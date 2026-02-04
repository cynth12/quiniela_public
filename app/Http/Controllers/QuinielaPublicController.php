<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use App\Models\Jugador;
use App\Models\Quiniela;
use App\Models\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;


class QuinielaPublicController extends Controller
{
    // Mostrar jornada y partidos por número
    public function jornadaPorNumero($numero, Request $request)
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

        $jugador = null;
        if ($request->has('jugador_id')) 
            { $jugador = Jugador::find($request->jugador_id); }

        return view('quiniela.public', compact('jornada', 'partidos', 'jugador'));
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
                'cantidad' => $totalGuardadas,
                'total' => $totalGuardadas * 10,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pagar($jugadorId) 
    { 
        $jugador = Jugador::with('quinielas')->findOrFail($jugadorId); 
        $cantidad = $jugador->quinielas->count(); 
        $total = $cantidad * 10;

    
    MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_TOKEN'));

    $client = new PreferenceClient();
    $preference = $client->create([
        "items" => [ 
            [ 
                "title" => "Pago Quiniela" . $jugador->nombre, 
                 "quantity" => 1, 
                 "currency_id" => "MXN",
                "unit_price" => 10.00,
            ]
        ],
        "back_urls" => [
            "success" => route('quiniela.exito'),
            "failure" => route('quiniela.fallo'),
            "pending" => route('quiniela.pendiente'),
            ],
            "auto_return" => "approved",
            "external_reference" => (string) $jugador->id,
        ]);

        return view('quiniela.pagar', compact('jugador', 'preference'));

        

     return redirect()->away($preference->init_point);
    }
        
        public function webhook(Request $request) 
        
        { $id = $request->input('data.id');
        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_TOKEN'));

            $client = new PaymentClient();
            $payment = $client->get($id);

            if ($payment && $payment->status === 'approved'){ 
                $jugadorId = (int) $payment->external_reference;
                Jugador::where('id', $jugadorId)->update(['pagada' => true]); 
            } 
            
            return response()->json(['status' => 'ok']); 
        } 
    }

