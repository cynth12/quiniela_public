@extends('adminlte::page')

@section('title', '📅 Jornadas creadas')

@section('content_header')
    <h1>📅 Jornadas creadas</h1>
@stop

@section('content')
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Premio</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jornadas as $jornada)
        <tr>
            <td>Jornada {{ $jornada->numero }}</td>
            <td>{{ \Carbon\Carbon::parse($jornada->fecha)->format('d/m/Y') }}</td>
            <td>${{ $jornada->premio }}</td>
            <td>
                <a href="{{ route('jornada.show', $jornada->id) }}" class="btn btn-info">Ver</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop







JS




console.log('✅ Script quinielas.js cargado correctamente');

let quinielas = [];
const costoPorQuiniela = 10;

// Validar teléfono (10 dígitos)
function telefonoValido(numero) {
    return /^[0-9]{10}$/.test(numero);
}

// Agregar una quiniela al array
function agregarQuiniela() {
    const nombreInput = document.getElementById('nombre');
    const nombre = nombreInput.value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const numero = document.querySelector('input[name="numero"]').value; // jornada

    // Validar nombre y teléfono
    if (!nombre || !telefono) {
        mostrarPopup('Por favor ingresa tu nombre y número.');
        return;
    }

    if (!telefonoValido(telefono)) {
        mostrarPopup('Por favor ingresa un número de teléfono válido de 10 dígitos.');
        return;
    }

    // Bloquear el campo nombre después de la primera quiniela
    nombreInput.readOnly = true;

    const resultados = [];
    const totalPartidos = document.querySelectorAll('tbody tr').length;

    for (let i = 0; i < totalPartidos; i++) {
        const seleccion = document.querySelector(`input[name="resultados[${i}]"]:checked`);
        if (!seleccion) {
            mostrarPopup(`Selecciona una opción para todos los partidos.`);
            return;
        }
        resultados.push(seleccion.value);
    }

    const quiniela = {
        numero,
        nombre,
        telefono,
        resultados
    };

    quinielas.push(quiniela);

    actualizarResumen();
    mostrarQuinielas();
    limpiar();

    console.log('✅ Quiniela agregada:', quiniela);
}

// Generar resultados aleatorios
function aleatorio() {
    const totalPartidos = document.querySelectorAll('tbody tr').length;
    for (let i = 0; i < totalPartidos; i++) {
        const radios = document.querySelectorAll(`input[name="resultados[${i}]"]`);
        const random = Math.floor(Math.random() * radios.length);
        radios[random].checked = true;
    }
}

// Limpiar selección de radios
function limpiar() {
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(r => r.checked = false);
}

// Actualizar resumen de quinielas
function actualizarResumen() {
    const total = quinielas.length * costoPorQuiniela;
    document.getElementById('resumen').innerText = `${quinielas.length} quiniela(s) – Total: $${total} MXN`;
}

// Mostrar lista de quinielas agregadas
function mostrarQuinielas() {
    const contenedor = document.getElementById('listaQuinielas');
    contenedor.innerHTML = '';
    quinielas.forEach((q, index) => {
        const div = document.createElement('div');
        div.classList.add('alert', 'alert-light', 'mt-2');
        div.innerHTML = `<strong>#${index + 1} – ${q.nombre}</strong><br>${q.resultados.join(' – ')}`;
        contenedor.appendChild(div);
    });
}

// Guardar quinielas sin pagar
function guardarQuiniela() {
    if (quinielas.length === 0) {
        mostrarPopup('❌ No hay quinielas para guardar.');
        return;
    }

    const telefono = document.getElementById('telefono').value.trim();
    if (!telefonoValido(telefono)) {
        mostrarPopup('Por favor ingresa un número de teléfono válido de 10 dígitos.');
        return;
    }

    console.log('🔍 Quinielas que se van a guardar:', quinielas);

    fetch('/public/quiniela', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quinielas })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            mostrarPopup('✅ Tus quinielas se guardaron correctamente. Procede a realizar tu pago.');
            // Limpiar quinielas visuales
            quinielas = [];
            document.getElementById('listaQuinielas').innerHTML = '';
            document.getElementById('resumen').innerText = '';
            // Actualizar el link
            window.history.replaceState({}, '', '/quiniela');
        } else {
            mostrarPopup('❌ Error: ' + (data.error || 'No se pudo guardar.'));
        }
    })
    .catch(err => {
        console.error(err);
        mostrarPopup('❌ Error al guardar la quiniela.');
    });
}

