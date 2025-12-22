@extends('adminlte::page')

@section('title', 'Crear Jornada')

@section('content_header')
    <h1>Crear nueva jornada</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('jornada.store') }}">
    @csrf

    <div class="form-group">
        <label for="numero">🗓️ Número de jornada</label>
        <select name="numero" id="numero" class="form-control" required>
            @for ($j = 1; $j <= 17; $j++)
                <option value="{{ $j }}">Jornada {{ $j }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label for="fecha">📅 Fecha</label>
        <input type="text" name="fecha" id="fecha" class="form-control" placeholder="dd/mm/yyyy" required>
    </div>

    <div class="form-group">
        <label for="premio">💰 Premio estimado</label>
        <input type="number" step="0.01" name="premio" id="premio" class="form-control" placeholder="$" required>
    </div>

    <h4 class="mt-4">⚽ Partidos de la jornada</h4>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Local</th>
                <th>Visitante</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 3; $i++)
<tr>
    <td>
        {{ $i + 1 }}
        <input type="hidden" name="partidos[{{ $i }}][partido_numero]" value="{{ $i + 1 }}">
    </td>
    <td>
        <select name="partidos[{{ $i }}][equipo_local]" class="form-control" required>
            <option value="" disabled selected>Selecciona equipo local</option>
            @foreach ($equipos as $equipo)
                <option value="{{ $equipo }}">{{ $equipo }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="partidos[{{ $i }}][equipo_visitante]" class="form-control" required>
            <option value="" disabled selected>Selecciona equipo visitante</option>
            @foreach ($equipos as $equipo)
                <option value="{{ $equipo }}">{{ $equipo }}</option>
            @endforeach
        </select>
    </td>
</tr>
@endfor
        </tbody>
    </table>

    <button type="submit" class="btn btn-success mt-3">✅ Crear Jornada</button>
</form>

@stop
