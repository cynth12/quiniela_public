@extends('layouts.app')


@section('content')
<div class="container">
    <h2>📊 Cierre de Jornada #{{ $jornada->id }}</h2>
    <p><strong>Fecha:</strong> {{ $jornada->fecha }}</p>

    <div class="card mb-4">
        <div class="card-header">Resumen Económico</div>
        <div class="card-body">
            <p><strong>Total generado:</strong> ${{ number_format($estadistica->total_dinero_generado, 2) }}</p>
            <p><strong>Premio acumulado (70%):</strong> ${{ number_format($estadistica->premio_acumulado, 2) }}</p>
            <p><strong>Ganancia administradora (30%):</strong> ${{ number_format($estadistica->ganancia_administrador, 2) }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Ganadores 🏆</div>
        <div class="card-body">
            @forelse ($ganadores as $g)
                <p>
                    🥇 <strong>{{ $g->nombre_jugador }}</strong> ({{ $g->telefono }}) — 
                    {{ $g->aciertos }} aciertos — 
                    Premio estimado: ${{ number_format($estadistica->premio_acumulado / $ganadores->count(), 2) }}
                </p>
            @empty
                <p>No hubo ganadores con 9 aciertos esta jornada.</p>
            @endforelse
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Estadísticas Técnicas</div>
        <div class="card-body">
            <p><strong>Total de participantes:</strong> {{ $estadistica->total_participantes }}</p>
            <p><strong>Promedio de aciertos:</strong> {{ $estadistica->promedio_aciertos }}</p>
            <p><strong>Tendencias:</strong></p>
            <ul>
                <li>Local (L): {{ $estadistica->total_local }}</li>
                <li>Empate (E): {{ $estadistica->total_empate }}</li>
                <li>Visitante (V): {{ $estadistica->total_visitante }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
