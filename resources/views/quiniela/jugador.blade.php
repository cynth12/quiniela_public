@extends('adminlte::page')

@section('title', 'Quinielas del Jugador')

@section('content_header')
    <h1>Quinielas de {{ $jugador->nombre }} (📱 {{ $jugador->telefono }})</h1>
@endsection

@section('content')
    <div class="container">
        @foreach ($jugador->quinielas as $q)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-info text-white">
                    Quiniela #{{ $q->id }} – Jornada {{ $q->numero }}
                </div>
                <div class="card-body">
                    <p><strong>Resultados seleccionados:</strong></p>
                    <ul>
                        @foreach ($q->respuestas as $r)
                            <li>Partido {{ $r->partido_numero }} → {{ $r->respuesta }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

        @if ($jugador->quinielas->isEmpty())
            <div class="alert alert-warning">
                Este jugador aún no tiene quinielas registradas.
            </div>
        @endif
    </div>
@endsection
