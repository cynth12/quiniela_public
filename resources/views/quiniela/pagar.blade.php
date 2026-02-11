@extends('layouts.app')

@section('content')
    <h1>Pagar Quiniela</h1>
    <p>Jugador: {{ $jugador->nombre }}</p>
    <p>Total: {{ $preference->items[0]->unit_price * $preference->items[0]->quantity }} MXN</p>

    <a href="{{ $preference->init_point }}" target="_blank" class="btn btn-primary">
        💳 Ir a Mercado Pago
    </a>
@endsection