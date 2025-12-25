<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Respuestas;
use Illuminate\Http\Request;

class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $jugadores = Jugador::with('quinielas')->get();
    return view('jugadores.index', compact('jugadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Jugador $jugador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jugador $jugador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jugador $jugador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jugador $jugador)
    {
        foreach ($jugador->quinielas as $q) { $q->respuestas()->delete(); $q->delete(); } $jugador->delete(); 
        return redirect()->route('quiniela.index')->with('success', 'Jugador y sus quinielas eliminados correctamente.');
    }
}
