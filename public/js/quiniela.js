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

    // Validar nombre
    if (!nombre) {
        Swal.fire('Campo requerido', 'Coloca tu nombre para continuar.', 'warning');
        return;
    }

    // Validar teléfono
    if (!telefono) {
        Swal.fire('Campo requerido', 'Coloca tu número de celular para continuar.', 'warning');
        return;
    }

    if (!telefonoValido(telefono)) {
        Swal.fire('Teléfono inválido', 'Ingresa un número válido de 10 dígitos.', 'error');
        return;
    }

    // Bloquear el campo nombre después de la primera quiniela
    nombreInput.readOnly = true;

    const resultados = [];
    const totalPartidos = document.querySelectorAll('tbody tr').length;

    for (let i = 0; i < totalPartidos; i++) {
        const seleccion = document.querySelector(`input[name="resultados[${i}]"]:checked`);
        if (!seleccion) {
            Swal.fire('Faltan resultados', 'Selecciona una opción para todos los partidos.', 'warning');
            return;
        }
        resultados.push(seleccion.value);
    }

    const quiniela = { numero, nombre, telefono, resultados };
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
    document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
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
        Swal.fire('Sin quinielas', '❌ No hay quinielas para guardar.', 'warning');
        return;
    }

    const telefono = document.getElementById('telefono').value.trim();
    if (!telefonoValido(telefono)) {
        Swal.fire('Teléfono inválido', 'Ingresa un número válido de 10 dígitos.', 'error');
        return;
    }

    

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
            Swal.fire('¡Gracias por participar!', 'Tus quinielas se guardaron correctamente. Procede a tu pago.', 'success');
            const numero = quinielas[0]?.numero || 6; // usa el número de jornada de la primera quiniela

            quinielas = [];
            document.getElementById('listaQuinielas').innerHTML = '';
            
            const resumen = document.getElementById('resumen');
            resumen.innerHTML = `
            <p><strong>Total de quinielas:</strong> ${data.cantidad}</p>
            <p><strong>Total a pagar:</strong> $${data.total} MXN</p>
            <a href="/quiniela/pagar/${data.jugador_id}" class="btn btn-lg btn-success w-100 mt-3" style="font-size: 1.2rem; padding: 12px;">
                    💳 Pagar con Mercado Pago
                </a>
            `;
        
        } else {
            Swal.fire('Error', '❌ ' + (data.error || 'No se pudo guardar.'), 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', '❌ Ocurrió un problema al guardar la quiniela.', 'error');
    });

}

