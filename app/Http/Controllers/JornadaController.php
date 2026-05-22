<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ganador;
use App\Models\Quiniela;
use App\Models\Jornada;
use App\Models\Partido;
use App\Models\Resultado;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class JornadaController extends Controller
{
    public function index()
    {
        $jornadas = Jornada::orderBy('numero')->get();
        return view('jornada.index', compact('jornadas'));
    }

    public function create()
    {
        $equipos = ['América', 'Atlas', 'Chivas', 'Puebla', 'Cruz Azul', 'Pumas', 'Tigres', 'Toluca', 'Monterrey', 'Santos', 'Pachuca', 'Querétaro', 'Necaxa', 'Mazatlán', 'Juárez', 'León', 'Tijuana', 'Atlético San Luis'];
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

    // 👉 Aquí insertas el método cerrar
    public function cerrarSimple($id)
    {
        $jornada = Jornada::findOrFail($id);
        $jornada->cerrada = true;
        $jornada->save();

        return redirect()->back()->with('success', '⚠ Jornada cerrada, el link público ya no está disponible.');
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

    public function guardarAvance(Request $request, $numero)
{
    $jornada = Jornada::with('partidos')
        ->where('numero', $numero)
        ->firstOrFail();

    // guardar SOLO resultados llenados
    foreach ($request->resultados as $partido_numero => $resultado) {

        if (!empty($resultado)) {

            Resultado::updateOrCreate(
                [
                    'numero' => $numero,
                    'partido_numero' => $partido_numero
                ],
                [
                    'resultado_oficial' => strtoupper($resultado)
                ]
            );
        }
    }

    return redirect()
        ->route('jornada.avance', $numero)
        ->with('success', '📊 Avance actualizado correctamente.');
}

    public function cerrarPorNumero(Request $request, $numero)
{
    $jornada = Jornada::with('partidos')
        ->where('numero', $numero)
        ->firstOrFail();

    // guardar resultados oficiales
    foreach ($request->resultados as $partido_numero => $resultado) {

        Resultado::updateOrCreate(
            [
                'numero' => $numero,
                'partido_numero' => $partido_numero
            ],
            [
                'resultado_oficial' => strtoupper($resultado)
            ]
        );
    }

    // validar que TODOS estén llenos
    $completos = Resultado::where('numero', $numero)
        ->whereNotNull('resultado_oficial')
        ->count();

    if ($completos < $jornada->partidos->count()) {

        return back()->with(
            'error',
            'Completa todos los resultados oficiales antes de calcular ganadores.'
        );
    }

    // cerrar jornada
    $jornada->cerrada = true;
    $jornada->save();

    // borrar resultados anteriores
    Ganador::where('numero', $numero)->delete();

    // resultados oficiales
    $resultados = Resultado::where('numero', $numero)
        ->pluck('resultado_oficial', 'partido_numero');

    // quinielas participantes
    $quinielas = Quiniela::with('respuestas', 'jugador')
        ->where('numero', $numero)
        ->get();

    foreach ($quinielas as $quiniela) {

        $aciertos = 0;

        foreach ($quiniela->respuestas as $respuesta) {

            if (
                isset($resultados[$respuesta->partido_numero]) &&
                strtolower($resultados[$respuesta->partido_numero]) ==
                strtolower($respuesta->respuesta)
            ) {
                $aciertos++;
            }
        }

        Ganador::create([
            'numero' => $numero,
            'quiniela_id' => $quiniela->id,
            'jugador_id' => $quiniela->jugador_id,
            'aciertos' => $aciertos,
        ]);
    }

    return redirect()
        ->route('resultados.index')
        ->with('success', '🏆 Ganadores calculados correctamente.');
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
    $ganadores = Ganador::with('jugador', 'quiniela')
        ->orderBy('numero', 'desc')
        ->orderBy('posicion')
        ->orderByDesc('aciertos')
        ->get();

    return view('jornada.ganadores', compact('ganadores'));
}

public function avance($numero)
{
    $jornada = Jornada::with('partidos')
        ->where('numero', $numero)
        ->firstOrFail();

    $resultados = Resultado::where('numero', $numero)
        ->pluck('resultado_oficial', 'partido_numero');

    $quinielas = Quiniela::with('jugador', 'respuestas')
        ->where('numero', $numero)
        ->get();

    $reporte = [];

    foreach ($quinielas as $quiniela) {

        $aciertos = 0;

        foreach ($quiniela->respuestas as $respuesta) {

            if (
                isset($resultados[$respuesta->partido_numero]) &&
                strtolower($resultados[$respuesta->partido_numero]) ==
                strtolower($respuesta->respuesta)
            ) {
                $aciertos++;
            }
        }

        $reporte[] = [
            'jugador' => $quiniela->jugador->nombre ?? 'Sin nombre',
            'quiniela_id' => $quiniela->id,
            'aciertos' => $aciertos,
        ];
    }

    usort($reporte, function ($a, $b) {
        return $b['aciertos'] <=> $a['aciertos'];
    });

    return view('jornada.avance', compact(
        'jornada',
        'reporte',
        'resultados'
    ));
}

public function pdfFinal($numero)
{
    $jornada = Jornada::with('partidos')
        ->where('numero', $numero)
        ->firstOrFail();

    // resultados oficiales
    $resultados = Resultado::where('numero', $numero)
        ->pluck('resultado_oficial', 'partido_numero');

    // TODAS las quinielas participantes
    $quinielas = Quiniela::with('jugador', 'respuestas')
        ->where('numero', $numero)
        ->get();

    $reporte = [];

    foreach ($quinielas as $quiniela) {

        $aciertos = 0;

        $detallePartidos = [];

        foreach ($jornada->partidos as $partido) {

            $respuestaJugador = $quiniela->respuestas
                ->where('partido_numero', $partido->partido_numero)
                ->first();

            $respuesta = $respuestaJugador->respuesta ?? '-';

            $resultadoOficial = $resultados[$partido->partido_numero] ?? '-';

            $acerto = strtolower($respuesta) == strtolower($resultadoOficial);

            if ($acerto) {
                $aciertos++;
            }

            $detallePartidos[] = [
                'partido' => $partido->local . ' vs ' . $partido->visitante,
                'respuesta' => strtoupper($respuesta),
                'oficial' => strtoupper($resultadoOficial),
                'acerto' => $acerto,
            ];
        }

        $reporte[] = [
            'jugador' => $quiniela->jugador->nombre,
            'quiniela_id' => $quiniela->id,
            'aciertos' => $aciertos,
            'detalle' => $detallePartidos,
        ];
    }

    // ordenar por más aciertos
    usort($reporte, function ($a, $b) {
        return $b['aciertos'] <=> $a['aciertos'];
    });

    $pdf = Pdf::loadView(
        'pdf.ganadores-final',
        compact('jornada', 'reporte')
    );

    return $pdf->download('resultado-final-jornada-'.$numero.'.pdf');
}


public function pdfAvance($numero)
{
    $jornada = Jornada::with('partidos')
        ->where('numero', $numero)
        ->firstOrFail();

    $resultados = Resultado::where('numero', $numero)
        ->pluck('resultado_oficial', 'partido_numero');

    $quinielas = Quiniela::with('jugador', 'respuestas')
        ->where('numero', $numero)
        ->get();

    foreach ($quinielas as $quiniela) {

    $aciertos = 0;

    foreach ($quiniela->respuestas as $respuesta) {

        if (
            isset($resultados[$respuesta->partido_numero]) &&
            strtolower($resultados[$respuesta->partido_numero]) ==
            strtolower($respuesta->respuesta)
        ) {
            $aciertos++;
        }
    }

    $quiniela->aciertos = $aciertos;
}

$quinielas = $quinielas->sortByDesc('aciertos');

    $pdf = Pdf::loadView(
        'pdf.avance',
        compact(
            'jornada',
            'quinielas',
            'resultados'
        )
    );

    

    return $pdf->download(
        'avance-jornada-'.$numero.'.pdf'
    );
}
}
