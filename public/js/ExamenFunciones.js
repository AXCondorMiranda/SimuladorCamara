// Definir variables globales
let correctCounter = parseInt(sessionStorage.getItem("correctCounter")) || 0;
let incorrectCounter = parseInt(sessionStorage.getItem("incorrectCounter")) || 0;
let testId = document.body.getAttribute("data-test-id");

if (!testId) {
    console.error("❌ testId no está definido. Verifica que el atributo data-test-id esté presente en el HTML.");
}

document.addEventListener("DOMContentLoaded", function () {
    console.log("🔵 [Inicio] Script cargado correctamente.");

    // Reiniciar contadores al cargar el examen
    correctCounter = 0;
    incorrectCounter = 0;
    sessionStorage.setItem("correctCounter", "0");
    sessionStorage.setItem("incorrectCounter", "0");

    // Actualizar contadores en la vista
    let correctCounterElement = document.getElementById("correctCounter");
    let incorrectCounterElement = document.getElementById("incorrectCounter");

    if (correctCounterElement) correctCounterElement.textContent = `Correctas: ${correctCounter}`;
    if (incorrectCounterElement) incorrectCounterElement.textContent = `Incorrectas: ${incorrectCounter}`;

    let listContainer = document.getElementById("listQuestions");
    let preguntasContainer = document.getElementById("preguntasListado");

    // ** Renderizar preguntas **
    listaPreguntas.forEach((element, index) => {
        console.log(`🔍 Creando estructura para Pregunta ID: ${element.id}`);

        // Crear número de pregunta en lista lateral
        const li = document.createElement("li");
        li.setAttribute("data-question-id", element.id);
        li.className = "no-respondida";
        li.innerHTML = `
            <input type="radio" class="form-check-input" name="preguntaSeleccionada" data-index="${index}">
            <label>${index + 1}</label>
        `;
        listContainer.appendChild(li);

        // Crear contenedor de la pregunta
        let preguntaDiv = document.createElement("div");
        preguntaDiv.id = `pregunt${element.id}`;
        preguntaDiv.className = "hidden";
        preguntaDiv.style.display = "none";
        preguntaDiv.innerHTML = `
            <p>${element.nombre}</p>
            <button type="button" class="btn btn-light btn-sm" onclick="leerPregunta(${element.id})">
                <i class="fas fa-volume-up"></i> Escuchar
            </button>
            <ul class="list-unstyled" id="listaPreguntas${element.id}"></ul>
            <button type="button" id="btnCorregir${element.id}" class="btn btn-primary"
                onclick="corregirPregunta(${index}, ${element.id})">
                Corregir ahora
            </button>
        `;
        preguntasContainer.appendChild(preguntaDiv);

        // Crear opciones de respuesta
        let contenedorOpciones = document.getElementById(`listaPreguntas${element.id}`);
        element.respuestas.forEach((res) => {
            console.log(`✅ Generando opción: ${res.respuesta} - Correcta: ${res.is_correct}`);
            let respuestaDiv = document.createElement("div");
            respuestaDiv.innerHTML = `
                <input type="radio" name="respuesta${element.id}" class="form-check-input" data-correct="${res.is_correct}">
                <label>${res.respuesta}</label>
            `;
            contenedorOpciones.appendChild(respuestaDiv);
        });
    });

    // Evento de selección de pregunta
    document.querySelectorAll('input[name="preguntaSeleccionada"]').forEach(input => {
        input.addEventListener("change", function () {
            let questionId = this.closest("li").getAttribute("data-question-id");
            mostrarPregunta(questionId);
        });
    });

    // ** Finalizar examen **
    document.getElementById("btnSubmitExam").addEventListener("click", function () {
        let respuestasGuardadas = localStorage.getItem("respuestasExamen") || "{}";
        let sessionTestId = document.body.getAttribute("data-session-id");

        if (respuestasGuardadas === "{}") {
            alert("No se han encontrado respuestas guardadas. Verifica si has respondido preguntas.");
            return;
        }

        fetch("/guardar-respuestas", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                respuestasExamen: respuestasGuardadas,
                testSessionId: testId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(`Error: ${data.error}`);
                } else {
                    console.log("✅ Examen finalizado correctamente.");
                    window.location.href = data.redirect;
                }
            })
            .catch(error => console.error("❌ Error al enviar el examen:", error));

    });





    // ** Función para mostrar una pregunta seleccionada **
    function mostrarPregunta(questionId) {
        document.querySelectorAll(".hidden").forEach(el => el.style.display = "none");
        let preguntaSeleccionada = document.getElementById(`pregunt${questionId}`);
        if (preguntaSeleccionada) {
            preguntaSeleccionada.style.display = "block";
        }
    }

    // ** Función para corregir una pregunta **
    function corregirPregunta(index, questionId) {
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
            }

            if (esCorrectaRespuesta) {
                respuestaCorrecta = respuesta.nextElementSibling;
            }

            // Deshabilitar todas las respuestas después de corregir
            respuesta.disabled = true;
        });

        if (!seleccionada) {
            console.warn(`⚠ No se seleccionó ninguna respuesta en la pregunta ${questionId}`);
            return;
        }

        // Si la respuesta fue incorrecta, marcar la correcta en verde
        if (!esCorrecta && respuestaCorrecta) {
            respuestaCorrecta.style.backgroundColor = "green";
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
        respuestasGuardadas[testId] = respuestasGuardadas[testId] || {};

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


    // ** Función para leer la pregunta en voz alta **
    function leerPregunta(questionId) {
        let preguntaTexto = document.querySelector(`#pregunt${questionId} p`).textContent;
        let speech = new SpeechSynthesisUtterance(preguntaTexto);
        speech.lang = "es-ES";
        speech.rate = 0.7;
        speech.pitch = 1.5;
        window.speechSynthesis.speak(speech);
    }
    window.corregirPregunta = corregirPregunta;
});
