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
                    <td>{{ $ganador->quiniela->id ?? 'Sin quiniela' }}</td>
                    <td>{{ $ganador->jugador->nombre ?? 'Sin jugador' }}</td>
                    <td>
                        @if ($ganador->posicion == 1)
                            🥇 Primer lugar
                        @elseif($ganador->posicion == 2)
                            🥈 Segundo lugar
                        @elseif($ganador->posicion == 3)
                            🥉 Tercer lugar
                        @endif
                    </td>
                    <td>{{ $ganador->aciertos }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay ganadores registrados aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('ganadores.pdf', $ganadores->first()->numero ?? 0) }}"
   class="btn btn-danger mb-3">
    📄 Descargar PDF Final
</a>
@endsection
