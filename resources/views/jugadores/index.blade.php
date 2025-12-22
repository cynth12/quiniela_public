@extends('adminlte::page')

@section('title', 'Jugadores')

@section('content_header')
    <h1>Panel de Jugadores</h1>
@stop

@section('content')
    <div class="card">
       <h2>Lista de jugadores registrados</h2>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Jornada</th>
            <th>Quinielas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jugadores as $j)
    <tr>
        <td>{{ $j->id }}</td>
        <td>{{ $j->nombre }}</td>
        <td>{{ $j->telefono }}</td>
        <td>{{ $j->numero }}</td>
        <td>{{ $j->quinielas->count() }} quinielas</td>
    </tr>
@endforeach
    </tbody>
</table>

    </div>
@stop


