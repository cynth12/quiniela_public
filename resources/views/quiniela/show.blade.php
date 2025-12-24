@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>QUINIELA</h1>

@section('content')



<h1>Quiniela #{{ $quiniela->id }}</h1>
<p><strong>Jugador ID:</strong> {{ $quiniela->jugador_id }}</p>
<p><strong>Nombre:</strong> {{ $quiniela->jugador->nombre }} ({{ $quiniela->jugador->telefono }})</p>
<p><strong>Jornada:</strong> {{ $quiniela->numero }}</p>

<h3>Respuestas</h3>
<ul>
    @foreach($quiniela->respuestas as $r)
        <li>Partido {{ $r->partido_numero }} → 
            @if($r->respuesta === 'L') 🏠 Local
            @elseif($r->respuesta === 'E') ⚖️ Empate
            @elseif($r->respuesta === 'V') ✈️ Visitante
            @else ❓
            @endif
        </li>
    @endforeach
</ul>

@endsection



@stop