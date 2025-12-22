@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <h1>Gestión de Pagos</h1>
@stop
@section('content')

    <div class="container">



        {{-- Tabla de historial de pagos --}}
        <div class="card">
    <div class="card-header bg-primary text-white">
        Historial de Pagos
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-green">
                <tr>
                    <th>ID</th>
                    <th>Jugador</th>
                    <th>Jornada</th>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Comprobante</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                    <tr>
                        <td>{{ $pago->id }}</td>
                        <td>{{ $pago->jugador->nombre ?? 'Sin nombre' }}</td>
                        <td>{{ $pago->numero }}</td>
                        <td>${{ number_format($pago->monto, 2) }}</td>
                        <td>{{ $pago->fecha_pago }}</td>
                        <td>
                            @if ($pago->comprobante_pdf)
                                <a href="{{ asset('storage/' . $pago->comprobante_pdf) }}" target="_blank"
                                   class="btn btn-sm btn-success">
                                    Descargar PDF
                                </a>
                            @else
                                <span class="text-muted">No disponible</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Seguro que quieres eliminar este pago y su comprobante?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay pagos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection




    