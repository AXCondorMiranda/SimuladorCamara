document.addEventListener("DOMContentLoaded", function () {
    mostrarResumen();
});

function mostrarResumen() {
    const contenedor = document.getElementById("resumenExamen");

    if (!contenedor) {
        console.error("❌ No se encontró el contenedor para mostrar los resultados.");
        return;
    }

    // 🔹 Limpiar el contenedor antes de renderizar
    contenedor.innerHTML = "";

    if (!resultados || resultados.length === 0) {
        contenedor.innerHTML = `<p class="text-danger">No hay resultados disponibles.</p>`;
        return;
    }

    resultados.forEach((resultado) => {
        let preguntaTexto = resultado.question ? resultado.question.texto : "Pregunta no disponible";
        let respuestaUsuario = resultado.respuesta;
        let respuestaCorrecta = resultado.question ? resultado.question.respuesta_correcta : "No disponible";
        let esCorrecta = resultado.es_correcta;

        // 🔹 Crear el contenedor del resultado
        let resultadoDiv = document.createElement("div");
        resultadoDiv.classList.add("resultado-item", "p-3", "mb-3", "rounded", "shadow-sm");
        resultadoDiv.style.backgroundColor = esCorrecta ? "#d4edda" : "#f8d7da"; // Verde si es correcta, rojo si es incorrecta

        resultadoDiv.innerHTML = `
            <h5 class="fw-bold">${preguntaTexto}</h5>
            <p><strong>Tu respuesta:</strong> ${respuestaUsuario}</p>
            ${!esCorrecta ? `<p class="text-danger"><strong>Respuesta correcta:</strong> ${respuestaCorrecta}</p>` : ""}
        `;

        contenedor.appendChild(resultadoDiv);
    });
}
