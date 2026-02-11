@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h2>Pagar Quiniela</h2>

        <p><strong>Jugador:</strong> {{ $jugador->nombre }}</p>
        <p><strong>Total a pagar:</strong> ${{ $quinielas->count() * 10 }} MXN</p>

        <h4>Quinielas:</h4>
        <ul class="list-group">
            @foreach ($quinielas as $quiniela)
                <li class="list-group-item">
                    <strong>Partido:</strong> {{ $quiniela->partido->nombre ?? 'Sin partido' }}<br>
                    <strong>Elección:</strong>
                    @if ($quiniela->resultado === 'L')
                        Local 🟦
                    @elseif ($quiniela->resultado === 'E')
                        Empate 🟨
                    @elseif ($quiniela->resultado === 'V')
                        Visitante 🟥
                    @else
                        Sin selección
                    @endif
                </li>
            @endforeach
        </ul>

        <a href="{{ $preference->init_point }}" target="_blank" class="btn btn-success btn-lg mt-4">
            💳 Ir a Mercado Pago
        </a>
    </div>
@endsection
