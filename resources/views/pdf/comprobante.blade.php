<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background-color: #ffffff;
            color: #333;
        }
        h2 {
            color: #2c3e50;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .quiniela {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #eef;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <img src="{{public_path ('img/logo-zas.jpeg') }}" width="120" style="margin-bottom: 10px;">
    <h2>Comprobante de Pago</h2>

    <p><strong>Jugador ID:</strong> {{ $pago->jugador->id }}</p>
    <p><strong>Jugador:</strong> {{ $pago->jugador->nombre }}</p>
    <p><strong>Jornada:</strong> {{ $pago->numero }}</p>
    <p><strong>Total pagado:</strong> ${{ $pago->monto }}</p>
    <p><strong>Fecha:</strong> {{ $pago->fecha_pago }}</p>

    <h3>Quinielas enviadas</h3>
    @foreach($quinielas as $quiniela)
        <div class="quiniela">
            <p><strong>Quiniela #{{ $quiniela->id }}</strong></p>
            <ul>
                @foreach($quiniela->respuestas as $respuesta)
                    <li>Partido {{ $respuesta->partido_numero }} → {{ $respuesta->respuesta }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <div class="footer">
        Este comprobante celebra tu participación legítima en la jornada.  
        Gracias por confiar en Clic conectaZAS! ⚡️
    </div>
</body>
</html>

