@extends('adminlte::page')

@section('title', 'Jugadores Archivados')

@section('content_header')
    <h1>Jugadores Archivados</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h3 class="card-title">Listado de jugadores archivados</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jugador</th>
                        <th>Teléfono</th>
                        <th>Jornada</th>
                        <th>Quinielas</th>
                        <th>Pagos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jugadoresArchivados as $jugador)
                        <tr>
                            <td>{{ $jugador->id }}</td>
                            <td>{{ $jugador->nombre }}</td>
                            <td>{{ $jugador->telefono }}</td>
                            <td>
                                {{ $jugador->quinielas->pluck('numero')->implode(', ') }}
                            </td>
                            <td>{{ $jugador->quinielas->count() }}</td>
                            <td>{{ $jugador->pagos->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
