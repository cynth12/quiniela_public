@extends('layouts.simple')
@section('content')
    <div class="container mt-4">
        <h2>Pagar Quiniela</h2>

        <p><strong>Jugador:</strong> {{ $jugador->nombre }}</p>
        <p><strong>Total a pagar:</strong> ${{ $quinielas->count() * 10 }} MXN</p>

        <h4>Quinielas:</h4>
        <ul class="list-group">
            @foreach ($quinielas as $quiniela)
    <div class="card mb-3">
        <div class="card-header">
            Quiniela #{{ $quiniela->numero_quiniela }}
        </div>
        <div class="card-body">
            <ul>
                @foreach ($quiniela->respuestas as $respuesta)
                    <li>
                        Partido {{ $respuesta->partido_numero }} →
                        @if ($respuesta->respuesta === 'L')
                            🟦 Local
                        @elseif ($respuesta->respuesta === 'E')
                            🟨 Empate
                        @elseif ($respuesta->respuesta === 'V')
                            🟥 Visitante
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach

        </ul>

        <a href="{{ $preference->init_point }}" target="_blank" class="btn btn-success btn-lg mt-4">
            💳 Ir a Mercado Pago
        </a>
    </div>
@endsection
