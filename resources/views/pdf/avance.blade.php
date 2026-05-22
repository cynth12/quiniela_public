<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>
        Avance Jornada {{ $jornada->numero }}
    </title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1{
            text-align: center;
            margin-bottom: 5px;
        }

        h3{
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th{
            background: #343a40;
            color: white;
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        td{
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        .ok{
            font-weight: bold;
        }

        .fail{
            color: #999;
        }

    </style>
</head>

<body>

    <h1>
         Avance Jornada {{ $jornada->numero }}
    </h1>


<br><br>

<h3>
    Resultados parciales
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

            @php

                $resultado = $resultados[$partido->partido_numero] ?? null;

            @endphp

            @if($resultado)

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

                        {{ strtoupper($resultado) }}

                    </td>

                </tr>

            @endif

        @endforeach

    </tbody>

</table>

    <h3>
        Resultados parciales
    </h3>

    <table>

        <thead>

            <tr>

                <th>#</th>

                <th>Jugador</th>

                <th>Quiniela</th>

                <th>Aciertos</th>

                @foreach($jornada->partidos as $partido)

                    <th>
                        {{ $partido->local }}
                        <br>
                        vs
                        <br>
                        {{ $partido->visitante }}
                    </th>

                @endforeach

            </tr>

        </thead>

        <tbody>

            @php $posicion = 1; @endphp

            @foreach($quinielas->sortByDesc('aciertos') as $quiniela)

                <tr>

                    <td>
                        {{ $posicion++ }}
                    </td>

                    <td>
                        {{ $quiniela->jugador->nombre }}
                    </td>

                    <td>
                        #{{ $quiniela->id }}
                    </td>

                    <td>
                        {{ $quiniela->aciertos }}
                    </td>

                    @foreach($jornada->partidos as $partido)

                        @php

                            $respuesta = $quiniela->respuestas
                                ->where('partido_numero', $partido->partido_numero)
                                ->first();

                            $resultado = $resultados[$partido->partido_numero] ?? null;

                            $correcto = $resultado &&
                                        $respuesta &&
                                        strtoupper($resultado) ==
                                        strtoupper($respuesta->respuesta);

                        @endphp

                        <td>

                            @if($resultado)

                                @if($correcto)

                                    ✅

                                @else

                                    ❌

                                @endif

                                <br>

                                {{ $respuesta->respuesta ?? '-' }}

                            @else

                                —

                            @endif

                        </td>

                    @endforeach

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>