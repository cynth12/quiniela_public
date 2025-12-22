@extends('adminlte::page')

@section('title', 'Jornadas')

@section('content')
    <div class="container">
        <h2 class="mb-4">📊 Resultados Oficiales por Jornada</h2>

        @foreach ($jornadas as $jornada)
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    Jornada #{{ $jornada->numero }} – {{ \Carbon\Carbon::parse($jornada->fecha)->format('d/m/Y') }}
                </div>
                <div class="card-body">
                    @if ($jornada->partidos->count())
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th># Partido</th>
                                    <th>Local</th>
                                    <th>Visitante</th>
                                    <th>Resultado Oficial</th>
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
                    @else
                        <p class="text-muted">No hay partidos registrados para esta jornada.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
