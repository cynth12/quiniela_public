console.log('✅ Script quinielas.js cargado correctamente');

let quinielas = [];
const costoPorQuiniela = 10;
let quinielasGuardadas = false; // bandera para evitar duplicados

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

    if (!nombre) {
        Swal.fire('Campo requerido', 'Coloca tu nombre para continuar.', 'warning');
        return;
    }
    if (!telefono) {
        Swal.fire('Campo requerido', 'Coloca tu número de celular para continuar.', 'warning');
        return;
    }
    if (!telefonoValido(telefono)) {
        Swal.fire('Teléfono inválido', 'Ingresa un número válido de 10 dígitos.', 'error');
        return;
    }

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

// Guardar quinielas y enviar por WhatsApp
function guardarQuiniela() {
    if (quinielas.length === 0) {
        Swal.fire('Sin quinielas', '❌ No hay quinielas para enviar.', 'warning');
        return;
    }

    const telefono = document.getElementById('telefono').value.trim();
    if (!telefonoValido(telefono)) {
        Swal.fire('Teléfono inválido', 'Ingresa un número válido de 10 dígitos.', 'error');
        return;
    }

    // Guardar en la base de datos
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
            Swal.fire('¡Gracias por participar!', 'Tus quinielas se guardaron correctamente. Se abrirá WhatsApp.', 'success');

            // Construir mensaje para WhatsApp
            let mensaje = "👤 Nombre: " + quinielas[0].nombre + "\n📱 Teléfono: " + quinielas[0].telefono + "\n\n";
            mensaje += "Quinielas registradas:\n";
            quinielas.forEach((q, index) => {
                mensaje += "Quiniela " + (index + 1) + ": " + q.resultados.join(' – ') + "\n";
                });
            
            mensaje += "\nPor favor envía tu comprobante de pago aquí.";

            // Tu número de WhatsApp
            let numeroDestino = "529843833329"; // cámbialo por tu número real
            let url = "https://wa.me/" + numeroDestino + "?text=" + encodeURIComponent(mensaje);

            window.open(url, "_blank");

            quinielasGuardadas = true;
            quinielas = [];
            document.getElementById('listaQuinielas').innerHTML = '';
            actualizarResumen();
        } else {
            Swal.fire('Error', '❌ ' + (data.error || 'No se pudo guardar.'), 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', '❌ Ocurrió un problema al guardar la quiniela.', 'error');
    });
}

