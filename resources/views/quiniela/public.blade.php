<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Quiniela ZAS – Jornada {{ $jornada['numero'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/quiniela.css') }}">

</head>

<body class="bg-light">
    <img src="{{ asset('img/logo-zas.jpeg') }}" width="120" style="margin-bottom: 10px;">

    <div class="container py-4">
        <h2 class="jornada-info">📆 Jornada {{ $jornada['numero'] }} – {{ $jornada['fecha'] }} – 💰
            {{ $jornada['premio'] }}</h2>

        @php
            $quinielasGuardadas = isset($jugador) && $jugador->quinielas->count() > 0;
        @endphp

       

        @if ($quinielasGuardadas)
            <div class="alert alert-info">
                ✅ Ya guardaste tu quiniela. No puedes modificarla en esta sesión.
            </div>
        @endif

        


        <div class="card shadow-sm">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Partidos de la jornada</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('quiniela.store') }}" id="formQuiniela">
                        @csrf

                        <input type="hidden" name="numero" value="{{ $jornada['numero'] }}">
                        <input type="hidden" name="resultados" id="resultadosInput">

                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>L</th>
                                    <th>Local</th>
                                    <th>E</th>
                                    <th>Visitante</th>
                                    <th>V</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partidos as $index => $partido)
                                    <tr>
                                        <td>{{ $partido['partido_numero'] ?? $index + 1 }}</td>
                                        <td><input type="radio" name="resultados[{{ $index }}]" value="L"
                                                {{ $quinielasGuardadas ? 'disabled' : '' }}></td>
                                        <td>{{ $partido['local'] }}</td>
                                        <td><input type="radio" name="resultados[{{ $index }}]" value="E"
                                                {{ $quinielasGuardadas ? 'disabled' : '' }}></td>
                                        <td>{{ $partido['visitante'] }}</td>
                                        <td><input type="radio" name="resultados[{{ $index }}]" value="V"
                                                {{ $quinielasGuardadas ? 'disabled' : '' }}></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="form-group mt-4" style="color: white;">
                            <label>👤 Tu nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                placeholder="Tu nombre" required>
                        </div>
                        <div class="form-group" style="color: white;">
                            <label>📱 Tu número</label>
                            <input type="text" name="telefono" id="telefono" class="form-control"
                                placeholder="Tu número" required>
                        </div>

                        <div class="row text-center mt-4">
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-primary w-100"
                                    onclick="agregarQuiniela()"{{ $quinielasGuardadas ? 'disabled' : '' }}>
                                    ➕ Agregar Quiniela
                                </button>


                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" onclick="aleatorio()" class="btn btn-warning w-100">🎲
                                    Aleatorio</button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" onclick="limpiar()" class="btn btn-secondary w-100">🧹
                                    Limpiar</button>
                            </div>
                        </div>

                    </form>

                    <div class="text-left mt-4" style="color: white;">
                        <button type="button" class="btn btn-primary btn-lg w-100"
                            onclick="guardarQuiniela()"{{ $quinielasGuardadas ? 'disabled' : '' }}>
                            💾 Guardar Quinielas
                        </button>
                        @if ($quinielasGuardadas && isset($jugador))
                            <a href="{{ route('quiniela.pagar', $jugador->id) }}" class="btn btn-success w-100 mt-2">
                                💳 Pagar con Mercado Pago
                            </a>
                        @endif



                        <!--<button type="button" class="btn btn-success btn-lg w-100" onclick="pagarConMercadoPago()">
                            💳 Pagar con Mercado Pago
                        </button>-->

                        <p class="mt-2 mb-1">Costo por quiniela: <strong>$10 MXN</strong></p>
                        <p id="resumen">0 quinielas – Total: $0 MXN</p>
                        <div id="listaQuinielas"></div>
                    </div>
                </div>
            </div>
            <script></script>
            <script src="{{ asset('js/quiniela.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
