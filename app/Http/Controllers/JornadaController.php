<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ganador;
use App\Models\Quiniela;
use App\Models\Jornada;
use App\Models\Partido;
use App\Models\Resultado;
use Carbon\Carbon;

class JornadaController extends Controller
{
    public function index()
    {
        $jornadas = Jornada::orderBy('numero')->get();
        return view('jornada.index', compact('jornadas'));
    }

    public function create()
    {
        $equipos = ['América', 'Atlas', 'Chivas', 'Cruz Azul', 'Pumas', 'Tigres', 'Toluca', 'Monterrey', 'Santos', 'Pachuca', 'Querétaro', 'Necaxa', 'Mazatlán', 'Juárez', 'León', 'Tijuana', 'Atlético San Luis'];
        return view('jornada.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $totalJornadas = Jornada::count();

        if ($totalJornadas >= 17) {
            return back()->with('error', 'Ya se han creado las 17 jornadas permitidas.');
        }

        $fechaFormateada = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');

        $jornada = Jornada::create([
            'numero' => $request->numero,
            'fecha' => $fechaFormateada,
            'premio' => $request->premio,
        ]);

        foreach ($request->partidos as $partido) {
            Partido::create([
                'numero' => $jornada->numero, // ✅ el nuevo campo que sí existe
                'partido_numero' => $partido['partido_numero'],
                'local' => $partido['equipo_local'],
                'visitante' => $partido['equipo_visitante'],
            ]);
        }

        return redirect()->route('jornadas.index')->with('success', 'Jornada creada correctamente.');
    }

    public function show($id)
    {
        $jornada = Jornada::with('partidos')->findOrFail($id);
        return view('jornada.show', compact('jornada'));
    }

    public function cerrar(Request $request, $id)
    {
        $jornada = Jornada::with('partidos')->findOrFail($id);

        foreach ($jornada->partidos as $partido) {
            $resultado = Resultado::where('numero', $jornada->numero)->where('partido_numero', $partido->partido_numero)->first();

            if (!$resultado || !$resultado->resultado_oficial) {
                return back()->with('error', 'Completa todos los resultados oficiales antes de cerrar la jornada.');
            }
        }

        $jornada->cerrada = true;
        $jornada->save();

        return redirect()->route('resultados.index', $jornada->numero)->with('success', 'Jornada cerrada correctamente. ¡Ya puedes ver los ganadores!');
    }

    public function cerrarPorNumero(Request $request, $numero)
    {
        $jornada = Jornada::with('partidos')->where('numero', $numero)->firstOrFail();

        foreach ($request->resultados as $partido_numero => $resultado) {
            Resultado::updateOrCreate(
                ['numero' => $numero, 'partido_numero' => $partido_numero],
                [
                    'resultado_oficial' => $resultado,
                ],
            );
        }

        $completos = Resultado::where('numero', $numero)->whereNotNull('resultado_oficial')->count();

        if ($completos < $jornada->partidos->count()) {
            return back()->with('error', 'Completa todos los resultados oficiales antes de cerrar la jornada.');
        }

        $jornada->cerrada = true;
        $jornada->save();

        // 🔥 Cálculo de ganadores
        $resultados = Resultado::where('numero', $numero)->pluck('resultado_oficial', 'partido_numero');

        $quinielas = Quiniela::with('respuestas', 'jugador')->where('numero', $numero)->get();

        foreach ($quinielas as $quiniela) {
            $aciertos = 0;

            foreach ($quiniela->respuestas as $respuesta) {
                if (isset($resultados[$respuesta->partido_numero]) && strtolower($resultados[$respuesta->partido_numero]) === strtolower($respuesta->respuesta)) {
                    $aciertos++;
                }
            }

            if (in_array($aciertos, [1, 2, 3])) {
                Ganador::create([
                    'numero' => $numero,
                    'quiniela_id' => $quiniela->id,
                    'jugador_id' => $quiniela->jugador_id,
                    'posicion' => $aciertos === 3 ? 'primer' : ($aciertos === 2 ? 'segundo' : 'tercero'),
                    'aciertos' => $aciertos,
                ]);
            }
        }

        return redirect()->route('resultados.index')->with('success', 'Jornada cerrada correctamente. ¡Ya puedes ver los ganadores!');
    }

    public function showByNumero($numero)
    {
        $jornada = Jornada::where('numero', $numero)->with('partidos')->firstOrFail();
        return view('jornada.show', compact('jornada'));
    }

    public function destroy($id)
    {
        $jornada = Jornada::with('partidos', 'resultados')->findOrFail($id);

        // Borrar partidos asociados
        $jornada->partidos()->delete();

        // Borrar resultados asociados
        $jornada->resultados()->delete();

        // Finalmente borrar la jornada
        $jornada->delete();

        return redirect()->route('jornadas.index')->with('success', 'Jornada y sus partidos/resultados eliminados correctamente.');
    }

    public function todosLosGanadores()
    {
        $ganadores = Ganador::with('jugador', 'quiniela')->orderBy('numero', 'desc')->get();

        return view('jornada.ganadores', compact('ganadores'));
    }
}
