@extends('adminlte::page')

@section('title', 'Ganadores')

@section('content')
<h2>Ganadores registrados</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Jornada</th>
            <th>Quiniela</th>
            <th>Jugador</th>
            <th>Posición</th>
            <th>Aciertos</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ganadores as $ganador)
            <tr>
                <td>{{ $ganador->numero }}</td>
                <td>#{{ $ganador->quiniela->id }}</td>
                <td>{{ $ganador->jugador->nombre }}</td>
                <td>{{ ucfirst($ganador->posicion) }}</td>
                <td>{{ $ganador->aciertos }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay ganadores registrados aún.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection

