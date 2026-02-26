@extends('layouts.simple')

@section('content')
<div class="container mt-4 text-center">
    <h2>❌ Tu pago fue rechazado o cancelado</h2>
    <p>Hola {{ $jugador->nombre ?? 'Jugador' }}, lamentablemente tu pago no se completó.</p>

    <h4>Quinielas guardadas:</h4>
    <ul class="list-group">
        @foreach($quinielas as $quiniela)
            <li class="list-group-item">
                Quiniela #{{ $quiniela->numero }} - Estado: {{ $quiniela->estado }}
            </li>
        @endforeach
    </ul>

    <p class="mt-3">Puedes intentarlo nuevamente para asegurar tu participación.</p>
    <a href="{{ route('pagos.generar', $jugador->id) }}" class="btn btn-danger mt-3">
        🔄 Reintentar pago
    </a>
</div>
@endsection
