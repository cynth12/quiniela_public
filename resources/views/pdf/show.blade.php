@extends('adminlte::page')

@section('title', 'Resultados de la jornada')

@section('content')
    <h1>🏆 Resultados – Jornada {{ $jornada->numero }}</h1>
    <p>📅 {{ $jornada->fecha }} – 💰 Premio: {{ $jornada->premio }}</p>

    {{-- Tabla de partidos con resultados oficiales --}}
    <table class="table text-center mb-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Resultado oficial</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jornada->partidos as $partido)
                <tr>
                    <td>{{ $partido->partido_numero }}</td>
                    <td>{{ $partido->local }}</td>
                    <td>{{ $partido->visitante }}</td>
                    @php
                        $resultado = strtolower($partido->resultado->resultado_oficial ?? '');
                        $simbolo = match ($resultado) {
                            'l' => '🏠 Local',
                            'v' => '✈️ Visitante',
                            'e' => '⚖️ Empate',
                            default => '❌ Sin resultado',
                        };
                    @endphp
                    <td>{{ $simbolo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-info">ℹ️ Estos son los resultados oficiales registrados para la jornada.</p>
@stop
