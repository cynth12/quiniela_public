@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>QUINIELA</h1>

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        <h1>Listado de Quinielas</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Jornada</th>
                    <th>Quinielas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jugadores as $j)
                    <tr>
                        <td>{{ $j->id }}</td>
                        <td>{{ $j->nombre }}</td>
                        <td>
                            {{ $j->quinielas->pluck('numero')->unique()->implode(', ') ?: '—' }}
                        </td>
                        <td>{{ $j->quinielas->count() }} quinielas</td>
                        <td>
                            <a href="{{ route('quiniela.jugador', $j->id) }}" class="btn btn-primary btn-sm">Ver</a>

                            @if ($j->quinielas->count() > 0)
                                <form action="{{ route('jugadores.destroy', $j->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar este jugador y todas sus quinielas?')">Borrar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection



@stop
