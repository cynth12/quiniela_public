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
                    <th>Jornada</th>
                    <th># Quinielas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quiniela as $quiniela)
                    <tr>
                        <td>{{ $quiniela->id }}</td>
                        <td>{{ $quiniela->jugador->nombre }}</td>
                        <td>{{ $quiniela->numero }}</td>
                        <td>{{ $quiniela->numero_quiniela }}</td>
                        <td>
                            <a href="{{ route('quiniela.show', $quiniela->id) }}" class="btn btn-primary btn-sm">Ver</a>
                            <form action="{{ route('quinielas.destroy', $quiniela->id) }}" method="POST"
                                style="display:inline;">
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
