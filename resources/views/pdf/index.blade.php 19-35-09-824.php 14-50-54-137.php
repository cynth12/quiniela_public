@extends('adminlte::page')

@section('title', '📅 Jornadas creadas')

@section('content_header')
    <h1>📅 Jornadas creadas</h1>
@stop

@section('content')
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Premio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jornadas as $jornada)
        <tr>
            <td>Jornada {{ $jornada->numero }}</td>
            <td>{{ \Carbon\Carbon::parse($jornada->fecha)->format('d/m/Y') }}</td>
            <td>${{ $jornada->premio }}</td>
            <td>
                <a href="{{ route('jornada.show', $jornada->id) }}" class="btn btn-info">Ver</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop
