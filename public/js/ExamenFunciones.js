// Definir variables globales
let correctCounter = parseInt(sessionStorage.getItem("correctCounter")) || 0;
let incorrectCounter = parseInt(sessionStorage.getItem("incorrectCounter")) || 0;
let testId = null; // <-- Se define aquí para que esté disponible globalmente
let testSessionId = null; // <-- Se define aquí para que esté disponible globalmente

document.addEventListener("DOMContentLoaded", function () {
    console.log("🔵 [Inicio] Script cargado correctamente.");

    // Limpiar respuestas almacenadas al iniciar un nuevo examen
    sessionStorage.clear();
    localStorage.removeItem("respuestasExamen");

    correctCounter = 0;
    incorrectCounter = 0;
    sessionStorage.setItem("correctCounter", "0");
    sessionStorage.setItem("incorrectCounter", "0");

    let testId = document.body.getAttribute("data-test-id");
    let testSessionId = document.body.getAttribute("data-session-id");

    console.log("📌 Test ID obtenido desde el HTML:", testId);
    console.log("📌 Test Session ID obtenido desde el HTML:", testSessionId);

    // Asegurar que `listaPreguntas` es un array válido
    if (!Array.isArray(listaPreguntas) || listaPreguntas.length === 0) {
        console.error("❌ No se recibieron preguntas en la vista. Verifica la base de datos.");
        return; // Salimos si no hay preguntas
    }

    console.log("📌 Preguntas recibidas en la vista:", listaPreguntas);

    let listContainer = document.getElementById("listQuestions");
    let preguntasContainer = document.getElementById("preguntasListado");

    // ** Renderizar preguntas **
    listaPreguntas.forEach((pregunta, index) => {
        console.log(`🔍 Creando estructura para Pregunta ID: ${pregunta.id}, Descripción: ${pregunta.description}`);

        // Crear número de pregunta en lista lateral
        const li = document.createElement("li");
        li.setAttribute("data-question-id", pregunta.id);
        li.className = "no-respondida";
        li.innerHTML = `
            <input type="radio" class="form-check-input" name="preguntaSeleccionada" data-index="${index}">
            <label>${index + 1}</label>
        `;
        listContainer.appendChild(li);

        // Crear contenedor de la pregunta
        let preguntaDiv = document.createElement("div");
        preguntaDiv.id = `pregunt${pregunta.id}`;
        preguntaDiv.className = "hidden";
        preguntaDiv.style.display = "none";
        preguntaDiv.innerHTML = `
            <p>${pregunta.description}</p>
            <button type="button" class="btn btn-light btn-sm" onclick="leerPregunta(${pregunta.id})">
                <i class="fas fa-volume-up"></i> Escuchar
            </button>
            <ul class="list-unstyled" id="listaPreguntas${pregunta.id}"></ul>
            <button type="button" id="btnCorregir${pregunta.id}" class="btn btn-primary"
                 onclick="corregirPregunta(${pregunta.id})">
                Corregir ahora
            </button>
        `;
        preguntasContainer.appendChild(preguntaDiv);

        // Validar que `pregunta.alternatives` sea un array válido
        if (!pregunta.alternatives || pregunta.alternatives.length === 0) {
            console.warn(`⚠️ La pregunta ID ${pregunta.id} no tiene alternativas.`);
            return;
        }

        let contenedorOpciones = document.getElementById(`listaPreguntas${pregunta.id}`);

        // Crear opciones de respuesta usando "alternatives"
        pregunta.alternatives.forEach((res) => {
            console.log(`✅ Generando opción para Pregunta ${pregunta.id}: ${res.description} - Correcta: ${res.is_correct}`);

            let respuestaDiv = document.createElement("div");
            respuestaDiv.innerHTML = `
                <input type="radio" name="respuesta${pregunta.id}" class="form-check-input" data-correct="${res.is_correct}">
                <label>${res.description}</label>
            `;
            contenedorOpciones.appendChild(respuestaDiv);
        });
    });

    // ** Evento de selección de pregunta **
    document.querySelectorAll('input[name="preguntaSeleccionada"]').forEach(input => {
        input.addEventListener("change", function () {
            let questionId = this.closest("li").getAttribute("data-question-id");
            mostrarPregunta(questionId);
        });
    });

    // ** Finalizar examen - Enviar respuestas al servidor **
    document.getElementById("btnSubmitExam").addEventListener("click", function () {
        const respuestas = obtenerRespuestas();

        if (!respuestas || Object.keys(respuestas).length === 0) {
            console.error("❌ No hay respuestas para enviar.");
            alert("Debes responder al menos una pregunta antes de finalizar.");
            return;
        }

        if (!testSessionId || testSessionId === "null") {
            alert("Error: Falta el ID de la sesión de examen.");
            return;
        }

        fetch("/guardar-respuestas", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                respuestasExamen: respuestas,
                test_id: testId,
                test_session_id: testSessionId
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la respuesta del servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("✅ Respuestas guardadas correctamente:", respuestas);
                alert("✅ Examen finalizado. Redirigiendo...");
                window.location.href = `/examen/resultado/${testSessionId}`;
            })
            .catch(error => {
                console.error("❌ Error al enviar respuestas:", error);
                alert("Hubo un error al guardar las respuestas. Inténtalo nuevamente.");
            });
    });

});

// ** Función para mostrar una pregunta seleccionada **
function mostrarPregunta(questionId) {
    document.querySelectorAll(".hidden").forEach(el => el.style.display = "none");
    let preguntaSeleccionada = document.getElementById(`pregunt${questionId}`);
    if (preguntaSeleccionada) {
        preguntaSeleccionada.style.display = "block";
    }
}

// ** Función para leer la pregunta en voz alta **
function leerPregunta(questionId) {
    let preguntaTexto = document.querySelector(`#pregunt${questionId} p`).textContent;
    let speech = new SpeechSynthesisUtterance(preguntaTexto);
    speech.lang = "es-ES";
    speech.rate = 0.7;
    speech.pitch = 1.5;
    window.speechSynthesis.speak(speech);
}
function corregirPregunta(questionId) {
    console.log(`\n[corregirPregunta] 🟡 Corrigiendo pregunta ${questionId}`);

    const respuestas = document.querySelectorAll(`input[name="respuesta${questionId}"]`);
    let seleccionada = false;
    let esCorrecta = false;
    let respuestaSeleccionada = null;
    let respuestaCorrecta = null;

    respuestas.forEach(respuesta => {
        const esCorrectaRespuesta = respuesta.getAttribute("data-correct") === "1";

        if (respuesta.checked) {
            seleccionada = true;
            esCorrecta = esCorrectaRespuesta;
            respuestaSeleccionada = respuesta.nextElementSibling;

            // Marcar la respuesta seleccionada en verde o rojo
            respuestaSeleccionada.style.backgroundColor = esCorrecta ? "green" : "red";
            respuestaSeleccionada.style.color = "white";
        }

        if (esCorrectaRespuesta) {
            respuestaCorrecta = respuesta.nextElementSibling;
        }

        // Deshabilitar todas las respuestas después de corregir
        respuesta.disabled = true;
    });

    if (!seleccionada) {
        console.warn(`⚠ No se seleccionó ninguna respuesta en la pregunta ${questionId}`);
        alert("Debe seleccionar una respuesta antes de corregir.");
        return;
    }

    // Si la respuesta fue incorrecta, marcar la correcta en verde
    if (!esCorrecta && respuestaCorrecta) {
        respuestaCorrecta.style.backgroundColor = "green";
        respuestaCorrecta.style.color = "white";
    }

    // Incrementar y guardar contadores en sessionStorage
    if (esCorrecta) {
        correctCounter++;
    } else {
        incorrectCounter++;
    }
    sessionStorage.setItem("correctCounter", correctCounter);
    sessionStorage.setItem("incorrectCounter", incorrectCounter);

    // Actualizar contadores en la vista
    document.getElementById("correctCounter").textContent = `Correctas: ${correctCounter}`;
    document.getElementById("incorrectCounter").textContent = `Incorrectas: ${incorrectCounter}`;

    // Guardar la respuesta en localStorage para enviarla en el examen
    let respuestasGuardadas = JSON.parse(localStorage.getItem("respuestasExamen")) || {};
    respuestasGuardadas[testId] = respuestasGuardadas[testId] || {};  // Aquí se usa testId correctamente

    respuestasGuardadas[testId][questionId] = {
        respuesta: respuestaSeleccionada ? respuestaSeleccionada.textContent : "",
        correcta: esCorrecta ? 1 : 0,
    };

    localStorage.setItem("respuestasExamen", JSON.stringify(respuestasGuardadas));
    console.log("📩 Respuestas almacenadas en localStorage:", respuestasGuardadas);

    // **🔹 Deshabilitar el botón de corrección después de corregir**
    let btnCorregir = document.getElementById(`btnCorregir${questionId}`);
    if (btnCorregir) {
        btnCorregir.disabled = true;
        btnCorregir.style.pointerEvents = "none"; // Evita clics adicionales
        btnCorregir.style.opacity = "0.5"; // Indica visualmente que está deshabilitado
    } else {
        console.warn(`⚠ No se encontró el botón de corregir para questionId: ${questionId}`);
    }
}

// Asegurar que la función esté accesible en el ámbito global
window.corregirPregunta = corregirPregunta;

// ** Función para obtener respuestas seleccionadas **
function obtenerRespuestas() {
    let respuestas = {};

    document.querySelectorAll("input[type='radio']:checked").forEach((respuesta) => {
        const preguntaId = respuesta.name.replace("respuesta", "").trim();
        if (!preguntaId || preguntaId === "preguntaSeleccionada") return;

        respuestas[preguntaId] = {
            respuesta: respuesta.nextElementSibling ? respuesta.nextElementSibling.textContent.trim() : "N/A",
            correcta: respuesta.getAttribute("data-correct") === "1" ? 1 : 0,
        };
    });

    console.log("📌 🔍 Respuestas capturadas antes de enviar:", respuestas);
    return respuestas;
}

// Asignar funciones al ámbito global
window.mostrarPregunta = mostrarPregunta;
window.leerPregunta = leerPregunta;
window.obtenerRespuestas = obtenerRespuestas;

console.log("✅ Script cargado completamente.");
