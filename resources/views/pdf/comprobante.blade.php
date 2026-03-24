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
    <h2>🧾 Comprobante de Pago</h2>
    <p><strong>Jugador:</strong> {{ $jugador->nombre }}</p>
    <p><strong>Jornada:</strong> {{ $pago->numero }}</p>
    <p><strong>Monto:</strong> ${{ number_format($pago->monto, 2) }}</p>
    <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($pago->estado) }}</p>

    
</body>
</html>
