// Activar selección visual de botones


// Obtener resultados desde botones visuales
function obtenerResultadosVisuales() {
  const partidos = document.querySelectorAll(".partido");
  let resultados = [];

  partidos.forEach(partido => {
    const nombrePartido = partido.getAttribute("data-partido");
    const seleccionada = partido.querySelector(".opcion.selected");
    const valor = seleccionada ? seleccionada.getAttribute("data-valor") : "—";
    resultados.push(`${nombrePartido}: ${valor}`);
  });

  return resultados;
}

// Aleatorizar selecciones visuales
function aleatorio() {
  const partidos = document.querySelectorAll(".partido");

  partidos.forEach(partido => {
    const opciones = partido.querySelectorAll(".opcion");
    const randomIndex = Math.floor(Math.random() * opciones.length);

    opciones.forEach(btn => btn.classList.remove("selected"));
    opciones[randomIndex].classList.add("selected");
  });
}

// Limpiar formulario y resultados
function limpiar() {
  document.getElementById("quinielaForm").reset();
  document.getElementById("resultado").innerHTML = "";

  // Limpiar selecciones visuales
  document.querySelectorAll(".opcion").forEach(btn => {
    btn.classList.remove("selected");
  });
}

// Variables globales
let quinielas = [];
const costoPorQuiniela = 10;

// Agregar quiniela a la lista
function agregarQuiniela() {
  const nombre = document.getElementById("nombre").value.trim();
  const telefono = document.getElementById("telefono").value.trim();
  const resultados = obtenerResultadosVisuales();

  if (!nombre) {
    alert("Por favor ingresa tu nombre.");
    return;
  }

  if (resultados.some(r => r.includes(": —"))) {
    alert("Por favor selecciona una opción para todos los partidos.");
    return;
  }

  const resumen = { nombre, telefono, resultados };
  quinielas.push(resumen);
  actualizarLista();
  limpiarFormularioSolo(); // ✅ Solo borra nombre y teléfono
}

  function limpiarFormularioSolo() {
  document.getElementById("nombre").value = "";
  document.getElementById("telefono").value = "";
  document.getElementById("resultado").innerHTML = "";
}





// Actualizar lista visual de quinielas
function actualizarLista() {
  const lista = document.getElementById("listaQuinielas");
  lista.innerHTML = "";

  quinielas.forEach((q, index) => {
    const item = document.createElement("div");
    item.classList.add("quiniela-item");
    item.innerHTML = `
      <strong>#${index + 1} – ${q.nombre}</strong><br>
      ${q.resultados.join("<br>")}
      <hr>
    `;
    lista.appendChild(item);
  });

  const total = quinielas.length * costoPorQuiniela;
  document.getElementById("resumenTotal").textContent = `${quinielas.length} quiniela(s) – Total: $${total} MXN`;

  const btnWhatsapp = document.getElementById("btnWhatsapp");
  btnWhatsapp.disabled = quinielas.length === 0;
}

// Borrar todas las quinielas
function borrarTodo() {
  quinielas = [];
  actualizarLista();
}

// Generar mensaje para WhatsApp
function generarMensajeWhatsapp() {
  let mensaje = `📋 *Resumen de Quinielas* (${quinielas.length} total)\n\n`;

  quinielas.forEach((q, index) => {
    mensaje += `*#${index + 1} – ${q.nombre}*\n`;
    q.resultados.forEach(r => {
      mensaje += `• ${r}\n`;
    });
    mensaje += `\n`;
  });

  const total = quinielas.length * costoPorQuiniela;
  mensaje += `💰 *Total:* $${total} MXN`;

  return encodeURIComponent(mensaje);
}

// Enviar por WhatsApp usando número ingresado
function enviarPorWhatsapp() {
  const numeroOrganizador = "5217331295000"; // ← Cambia este al número real del organizador
  const mensaje = generarMensajeWhatsapp();
  const url = `https://wa.me/${numeroOrganizador}?text=${mensaje}`;
  window.open(url, "_blank");
}


// Envío individual por submit (si lo usas)
document.getElementById("quinielaForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const nombre = document.getElementById("nombre").value.trim();
  const resultados = obtenerResultadosVisuales();

  if (resultados.some(r => r.includes(": —"))) {
    alert("Por favor selecciona una opción para todos los partidos.");
    return;
  }

  const mensaje = `📝 Quiniela de ${nombre}\n${resultados.join("\n")}`;
  const numeroDestino = "5217331295000"; // ← Reemplaza con tu número real
  const whatsappLink = `https://wa.me/${numeroDestino}?text=${encodeURIComponent(mensaje)}`;

  document.getElementById("resultado").innerHTML = `
    <p>Tu quiniela fue generada correctamente ✅</p>
    <a href="${whatsappLink}" target="_blank">📲 Compartir por WhatsApp</a>
  `;
});


function actualizarResumenFinal() {
  const partidos = document.querySelectorAll(".partido");
  const selecciones = [];

  partidos.forEach(partido => {
    const seleccionada = partido.querySelector(".opcion.selected");
    const valor = seleccionada ? seleccionada.getAttribute("data-valor") : "—";
    selecciones.push(valor);
  });

  document.getElementById("resumenFinalVisual").textContent = selecciones.join(", ");
}

document.querySelectorAll(".opcion").forEach(btn => {
  btn.addEventListener("click", () => {
    const grupo = btn.closest(".partido").querySelectorAll(".opcion");
    grupo.forEach(b => b.classList.remove("selected"));
    btn.classList.add("selected");
    actualizarResumenVisual();
    actualizarResumenFinal();
  });
});









