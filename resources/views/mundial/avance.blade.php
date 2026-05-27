@extends('adminlte::page')

@section('title', 'Avance')

@section('content_header')
    <h1>📊 Avance de fase {{ $jornada->numero }}</h1>
@stop

@section('content')

    <div class="card">

        <div class="card-header bg-primary text-white">
            Tabla parcial
        </div>

        <div class="card-body">

            <div class="mb-3">

        <a href="{{ route('avance.pdf', $jornada->numero) }}"
           class="btn btn-danger"
           target="_blank">

            <i class="fas fa-file-pdf"></i>
            Descargar PDF

        </a>

    </div>

            <table class="table table-bordered table-striped text-center">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jugador</th>
                        <th>Quiniela</th>
                        <th>Aciertos</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($reporte as $index => $fila)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>{{ $fila['jugador'] }}</td>

                            <td>#{{ $fila['quiniela_id'] }}</td>

                            <td>{{ $fila['aciertos'] }}</td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@stop
