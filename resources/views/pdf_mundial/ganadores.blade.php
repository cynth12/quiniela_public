<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>
        Resultados Jornada {{ $jornada->numero }}
    </title>

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin:20px;
        }

        h1{
            text-align:center;
            margin-bottom:5px;
        }

        h3{
            text-align:center;
            margin-top:0;
            color:#666;
        }

        table{
            width:100%;
            border-collapse: collapse;
            margin-top:20px;
        }

        th{
            background:#f2f2f2;
            border:1px solid #000;
            padding:6px;
            font-size:10px;
        }

        td{
            border:1px solid #000;
            padding:5px;
            text-align:center;
            font-size:10px;
        }

        .ganador{
            background:#d4edda;
            font-weight:bold;
        }

        .acierto{
            color:green;
            font-weight:bold;
        }

        .fallo{
            color:red;
            font-weight:bold;
        }

    </style>
</head>

<body>

    <h3>
        Resultados oficiales
    </h3>

    <table>

        <thead>

            <tr>
                <th>#</th>
                <th>Partido</th>
                <th>Resultado</th>
            </tr>

        </thead>

        <tbody>

            @foreach($jornada->partidos as $partido)

                <tr>

                    <td>
                        {{ $partido->partido_numero }}
                    </td>

                    <td>
                        {{ $partido->local }}
                        vs
                        {{ $partido->visitante }}
                    </td>

                    <td>

                        @php

                            $resultado = $partido->resultado->resultado_oficial ?? '-';

                        @endphp

                        {{ strtoupper($resultado) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>
 <br><br>

    <h1>
         Resultados Finales Jornada {{ $jornada->numero }}
    </h1>

    <h3>
        Fecha: {{ $jornada->fecha }}
    </h3>

    <table>

        <thead>

            <tr>

                <th>Jugador</th>

                <th>Quiniela</th>

                <th>Aciertos</th>

                @foreach($jornada->partidos as $partido)

                    <th>
                        {{ $partido->partido_numero }}
                    </th>

                @endforeach

            </tr>

        </thead>

        <tbody>

            @foreach($reporte as $fila)

                <tr class="{{ $fila['aciertos'] >= 6 ? 'ganador' : '' }}">

                    <td>
                        {{ $fila['jugador'] }}
                    </td>

                    <td>
                        Q-{{ $fila['quiniela_id'] }}
                    </td>

                    <td>
                        {{ $fila['aciertos'] }}
                    </td>

                    @foreach($fila['detalle'] as $detalle)

                        <td>

                            @if($detalle['acerto'])

                                <span class="acierto">
                                    O
                                </span>

                            @else

                                <span class="fallo">
                                    X
                                </span>

                            @endif

                            <br>

                            {{ $detalle['respuesta'] }}

                        </td>

                    @endforeach

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>