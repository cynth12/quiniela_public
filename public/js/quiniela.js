console.log('✅ Script quinielas.js cargado correctamente');
let quinielas = [];
const costoPorQuiniela = 10;

function agregarQuiniela() {
    const nombre = document.getElementById('nombre').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const numero = document.querySelector('input[name="numero"]').value; // ← jornada

    if (!nombre || !telefono) {
        mostrarPopup('Por favor ingresa tu nombre y número.');
        return;
    }

    const resultados = [];
    const totalPartidos = document.querySelectorAll('tbody tr').length;

    for (let i = 0; i < totalPartidos; i++) {
        const seleccion = document.querySelector(`input[name="resultados[${i}]"]:checked`);
        if (!seleccion) {
            mostrarPopup(`Por favor selecciona una opción para todos los partidos.`);
            return;
        }
        resultados.push(seleccion.value);
    }

    
    quinielas.push({ numero, nombre, telefono, resultados, });

    actualizarResumen();
    mostrarQuinielas();
    limpiar();
}

function aleatorio() {
    const totalPartidos = document.querySelectorAll('tbody tr').length;
    for (let i = 0; i < totalPartidos; i++) {
        const radios = document.querySelectorAll(`input[name="resultados[${i}]"]`);
        const random = Math.floor(Math.random() * radios.length);
        radios[random].checked = true;
    }
}

function limpiar() {
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(r => r.checked = false);
}

function actualizarResumen() {
    const total = quinielas.length * costoPorQuiniela;
    document.getElementById('resumen').innerText = `${quinielas.length} quiniela(s) – Total: $${total} MXN`;
}

function mostrarQuinielas() {
    const contenedor = document.getElementById('listaQuinielas');
    contenedor.innerHTML = '';
    quinielas.forEach((q, index) => {
        const div = document.createElement('div');
        div.classList.add('alert', 'alert-light', 'mt-2');
        div.innerHTML = `<strong>#${index + 1} – ${q.nombre}</strong><br>${q.resultados.map(r => r).join(' – ')}`;
        contenedor.appendChild(div);
    });
}

function pagarConMercadoPago() {
    if (quinielas.length === 0) {
        mostrarPopup('❌ No hay quinielas para pagar.');
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
        if (data.success && data.jugador_id) {
            window.location.href = `/pago/${data.jugador_id}`;
        } else {
            mostrarPopup('⚠️ Error: no se recibió el jugador para el pago.');
        }
    })
    .catch(err => {
        console.error(err);
        mostrarPopup('❌ Error al iniciar el pago.');
    });
}


function mostrarPopup(mensaje) {
    const popup = document.createElement('div');
    popup.classList.add('popup-aviso');
    popup.innerHTML = `
        <div class="popup-contenido">
            <p>${mensaje}</p>
            <button onclick="cerrarPopup()">OK</button>
        </div>
    `;
    document.body.appendChild(popup);
}

function cerrarPopup() {
    const popup = document.querySelector('.popup-aviso');
    if (popup) popup.remove();
}



function guardarQuiniela() {
    if (quinielas.length === 0) {
        mostrarPopup('❌ No hay quinielas para guardar.');
        return;
    }

    fetch('/public/quiniela', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({ quinielas }) // ← enviamos todas
        
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            mostrarPopup('✅ Quiniela guardada correctamente.');
            // Limpieza visual y lógica
            
        } else {
            mostrarPopup('❌ Error: ' + (data.error || 'No se pudo guardar.'));
        }
    })
    .catch(err => {
        console.error(err);
        mostrarPopup('❌ Error al guardar la quiniela.');
    });
}

    






