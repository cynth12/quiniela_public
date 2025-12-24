@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>QUINIELA</h1>

@section('content')

    <div class="container">
        <h1>Listado de Quinielas</h1>

        <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Jugador</th>
            <th>Teléfono</th>
            <th>Jornada</th>
            <th>Identificador</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($quinielas as $q)
            <tr>
                <td>{{ $q->id }}</td>
                <td>{{ $q->jugador->nombre }}</td>
                <td>{{ $q->jugador->telefono }}</td>
                <td>{{ $q->numero }}</td>
                <td>{{ $q->numero_quiniela }}</td>
                <td>
                    <a href="{{ route('quiniela.show', $q->id) }}" class="btn btn-primary btn-sm">Ver</a>
                    <a href="{{ route('quiniela.jugador', $q->jugador_id) }}" class="btn btn-info btn-sm">Ver por jugador</a>
                    <form action="{{ route('quinielas.destroy', $q->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar esta quiniela y todo lo relacionado?')">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
@endsection



@stop
