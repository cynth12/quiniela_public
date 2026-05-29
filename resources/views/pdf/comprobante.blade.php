<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color:#222;
            margin:30px;
        }

        .header{
            text-align:center;
            margin-bottom:20px;
        }

        .logo{
            text-align:center;
            margin-bottom:10px;
        }

        .logo img{
            width:120px;
        }

        .titulo{
            font-size:24px;
            font-weight:bold;
            color:#0a2342;
            margin-bottom:5px;
        }

        .subtitulo{
            font-size:14px;
            color:#666;
        }

        .info{
            margin-top:20px;
            margin-bottom:20px;
            width:100%;
        }

        .info td{
            padding:6px;
        }

        .card{
            border:1px solid #ccc;
            border-radius:8px;
            padding:15px;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        th{
            background:#0a2342;
            color:white;
            padding:8px;
            border:1px solid #ddd;
        }

        td{
            border:1px solid #ddd;
            padding:8px;
            text-align:center;
        }

        .quiniela-title{
            background:#f2f2f2;
            padding:8px;
            font-weight:bold;
            margin-top:20px;
            border-radius:5px;
        }

        .footer{
            margin-top:30px;
            text-align:center;
            font-size:11px;
            color:#777;
        }
    </style>
</head>

<body>

    <div class="logo">
        <img src="{{ public_path('img/logo-zas.jpeg') }}">
    </div>

    <div class="header">
        <div class="titulo">COMPROBANTE OFICIAL</div>
        <div class="subtitulo">Fase de Grupos Mundial 2026</div>
    </div>

    <div class="card">
        <table class="info">
            <tr>
                <td><strong> Jugador:</strong> {{ $jugador->nombre }}</td>
                <td><strong> Estado:</strong> {{ ucfirst($pago->estado) }}</td>
            </tr>

            <tr>
                <td><strong> Monto:</strong> ${{ number_format($pago->monto, 2) }}</td>
                <td><strong> Fecha:</strong> {{ $pago->fecha_pago }}</td>
            </tr>

            <tr>
                <td colspan="2">
                    <strong>🎟 Quinielas Registradas:</strong>
                    {{ $jugador->quinielas->where('numero', $pago->numero)->count() }}
                </td>
            </tr>
        </table>
    </div>

    @foreach($jugador->quinielas->where('numero', $pago->numero) as $index => $quiniela)

        <div class="quiniela-title">
            ⚽ Quiniela #{{ $index + 1 }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Partido</th>
                    <th>Elección</th>
                </tr>
            </thead>

            <tbody>

                @foreach($quiniela->respuestas as $respuesta)

                    @php
                        $partido = \App\Models\Partido::where('numero', $quiniela->numero)
                                    ->where('partido_numero', $respuesta->partido_numero)
                                    ->first();
                    @endphp

                    <tr>
                        <td>{{ $respuesta->partido_numero }}</td>

                        <td>
                            {{ $partido->local ?? '?' }}
                            VS
                            {{ $partido->visitante ?? '?' }}
                        </td>

                        <td>
                            <strong>{{ strtoupper($respuesta->respuesta) }}</strong>
                        </td>
                    </tr>

                @endforeach

            </tbody>
        </table>

    @endforeach

    <div class="footer">
        Quinielazas.com • Mundial 2026 ⚽
    </div>

</body>
</html>