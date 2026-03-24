<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { text-align: center; margin-top: 30px; }
        .titulo { font-size: 22px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="titulo">🏆 Comprobante de Pago</div>
        <p><strong>Jugador:</strong> {{ $jugador->nombre }}</p>
        <p><strong>Teléfono:</strong> {{ $jugador->telefono }}</p>

        @if($jugador->quinielas->count() > 0)
            <p><strong>Jornada:</strong> {{ $jugador->quinielas->first()->numero }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $pago = \App\Models\Pago::where('jugador_id', $jugador->id)
                        ->where('numero', $jugador->quinielas->first()->numero ?? null)
                        ->first();
                @endphp
                @if($pago)
                    <tr>
                        <td>${{ number_format($pago->monto, 2) }}</td>
                        <td>{{ $pago->fecha_pago }}</td>
                        <td>{{ ucfirst($pago->estado) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <p style="margin-top: 30px;">✔️ Este comprobante certifica que el jugador ha realizado su pago.</p>
    </div>
</body>
</html>

