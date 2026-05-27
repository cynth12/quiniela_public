@extends('layouts.simple')

@section('content')
<div class="container mt-4 text-center">
    <h2>🎉 ¡Gracias por participar!</h2>
    <p>Tu pago se ha registrado correctamente.</p>

    <h4>Quinielas pagadas:</h4>
    <ul class="list-group">
        @foreach($quinielas as $quiniela)
            <li class="list-group-item">
                Quiniela #{{ $quiniela->numero }} - Estado: {{ $quiniela->estado }}
            </li>
        @endforeach
    </ul>

    @if($pago && $pago->comprobante_pdf)
        <a href="{{ asset('storage/' . $pago->comprobante_pdf) }}" 
           class="btn btn-primary mt-3" target="_blank">
           📄 Descargar comprobante PDF
        </a>
    @endif
</div>
@endsection
