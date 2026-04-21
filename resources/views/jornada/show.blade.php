@extends('adminlte::page')

@section('title', 'Jornada ' . $jornada->numero)

@section('content_header')
    <h1>Jornada {{ $jornada->numero }} – {{ $jornada->fecha }} – 💰 {{ $jornada->premio }}</h1>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">Partidos de la jornada</h3>
        </div>
        <div class="card-body">

            @if (!$jornada->cerrada)
                {{-- Formulario único que guarda resultados y cierra jornada --}}
                <form method="POST" action="{{ route('jornadas.cerrar', $jornada->numero) }}">
                    @csrf
                    <table class="table text-center">
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
                                    <td>
                                        <input type="text" name="resultados[{{ $partido->partido_numero }}]"
                                            value="{{ old('resultados.' . $partido->partido_numero) }}" maxlength="1"
                                            class="form-control text-uppercase text-center" placeholder="L/V/E">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-warning mt-3">📅 Cerrar jornada y guardar resultados</button>
                    <form action="{{ route('jornada.cerrar', $jornada->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            🔒 Cerrar jornada
                        </button>
                    </form>
                </form>
            @else
                {{-- Jornada ya cerrada: mostrar resultados oficiales --}}
                <table class="table table-bordered text-center">
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
                            @php
                                $resultado = strtolower($partido->resultado->resultado_oficial ?? '');
                                $simbolo = match ($resultado) {
                                    'l' => '🏠 L',
                                    'v' => '✈️ V',
                                    'e' => '⚖️ E',
                                    default => '⏳ Pendiente',
                                };
                            @endphp
                            <tr>
                                <td>{{ $partido->partido_numero }}</td>
                                <td>{{ $partido->local }}</td>
                                <td>{{ $partido->visitante }}</td>
                                <td class="text-center">{{ $simbolo }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-success mt-3">✅ Esta jornada ya fue cerrada. Los resultados oficiales están registrados.</p>
            @endif

        </div>
    </div>
@stop
