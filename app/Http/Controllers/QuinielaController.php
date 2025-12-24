<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiniela;
use App\Models\Jugador;

class QuinielaController extends Controller
{
    /**
     * Listar todas las quinielas con su jugador.
     */
    public function index()
    {
        $quinielas = Quiniela::with('jugador')->latest()->get();
        return view('quiniela.index', compact('quinielas'));
    }

    /**
     * Mostrar detalle de una quiniela con su jugador y respuestas.
     */
    public function show($id)
    {
        $quiniela = Quiniela::with('jugador', 'respuestas')->findOrFail($id);
        return view('quiniela.show', compact('quiniela'));
    }

    /**
     * Eliminar una quiniela y sus respuestas.
     */
    public function destroy($id)
    {
        $quiniela = Quiniela::with('respuestas', 'jugador')->findOrFail($id);

        $quiniela->respuestas()->delete();
        $quiniela->delete();

        if ($quiniela->jugador->quinielas()->count() === 0) {
            $quiniela->jugador->delete();
        }

        return redirect()->back()->with('success', 'Quiniela eliminada correctamente.');
    }

    /**
     * Ver todas las quinielas y respuestas de un jugador.
     */
    public function verPorJugador($id)
    {
        $jugador = Jugador::with('quinielas.respuestas')->findOrFail($id);
        return view('quiniela.jugador', compact('jugador'));
    }
}

