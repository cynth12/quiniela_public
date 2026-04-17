<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ public_path('img/logo-zas.jpeg') }}" alt="Logo ZAS" width="120">
    </div>
    <h2>🧾 Comprobante de Pago</h2>
    <p><strong>Jugador:</strong> {{ $jugador->nombre }}</p>
    <p><strong>Jornada:</strong> {{ $pago->numero }}</p>
    <p><strong>Monto:</strong> ${{ number_format($pago->monto, 2) }}</p>
    <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($pago->estado) }}</p>

    <h3>📋 Quinielas registradas</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Resultados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jugador->quinielas->where('numero', $pago->numero) as $index => $quiniela)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @foreach($quiniela->respuestas as $respuesta)
                            {{ $respuesta->respuesta }}@if(!$loop->last) – @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
