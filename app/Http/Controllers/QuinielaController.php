<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiniela;
use App\Models\Respuestas;
use App\Models\Jugador;

class QuinielaController extends Controller
{
    // Mostrar todas las quinielas (si lo necesitas en el futuro)
    public function index()
    {
        $quiniela = Quiniela::with('jugador')->latest()->get();
        return view('quiniela.index', compact('quiniela'));
    }

    public function store(Request $request)
    {
        Quiniela::create($request->all());
        return redirect()->route('quiniela.index');
    }

    public function show($id)
{
    $quiniela = Quiniela::with('jugador', 'respuestas')->findOrFail($id);
    return view('quiniela.show', compact('quiniela'));
}

public function destroy($id)
{
    $quiniela = Quiniela::with('respuestas', 'jugador')->findOrFail($id);

    // Borrar respuestas
    $quiniela->respuestas()->delete();

    // Borrar quiniela
    $quiniela->delete();

    // Si el jugador ya no tiene otras quinielas, lo borramos también
    if ($quiniela->jugador->quinielas()->count() === 0) {
        $quiniela->jugador->delete();
    }

    return redirect()->back()->with('success', 'Quiniela eliminada correctamente.');
}
}


