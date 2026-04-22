@extends('adminlte::page')

@section('title', 'Jugadores')

@section('content_header')
    <h1>Panel de Jugadores</h1>
@stop

@section('content')
    <div class="card">
        <h2>Lista de jugadores registrados</h2>
        <form action="{{ route('jugadores.archivarTodos') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-dark mb-3">
                🗂️ Archivar todo
            </button>
        </form>


        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jugadores as $j)
                    <tr>
                        <td>{{ $j->id }}</td>
                        <td>{{ $j->nombre }}</td>
                        <td>{{ $j->telefono }}</td>
                        <td>
                            @if ($j->estado === 'pagado')
                                <span class="badge bg-success">Pagado ✅</span>
                            @else
                                <span class="badge bg-warning">Pendiente ⏳</span>
                            @endif
                        </td>
                        <td>
                            @if ($j->estado !== 'pagado')
                                <form action="{{ route('jugadores.marcarPagado', $j->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Marcar Pagado</button>
                                </form>
                            @endif

                            <form action="{{ route('jugadores.destroy', $j->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@stop
