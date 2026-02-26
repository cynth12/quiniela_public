@extends('layouts.simple')

@section('content')
<div class="container mt-4 text-center">
    <h2>⏳ Tu pago está pendiente</h2>
    <p>Hola {{ $jugador->nombre ?? 'Jugador' }}, tu pago aún no ha sido confirmado.</p>

    <h4>Quinielas guardadas:</h4>
    <ul class="list-group">
        @foreach($quinielas as $quiniela)
            <li class="list-group-item">
                Quiniela #{{ $quiniela->numero }} - Estado: {{ $quiniela->estado }}
            </li>
        @endforeach
    </ul>

    <p class="mt-3">Puedes volver a intentar el pago en cualquier momento desde tu enlace.</p>
    <a href="{{ route('pagos.generar', $jugador->id) }}" class="btn btn-warning mt-3">
        💳 Reintentar pago
    </a>
</div>
@endsection
