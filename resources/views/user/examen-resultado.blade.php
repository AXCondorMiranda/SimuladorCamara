@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Resultados del Examen</h2>
        
        <div id="resultados-container"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let resultados = @json($resultados);

            let container = document.getElementById("resultados-container");

            resultados.forEach(resultado => {
                let div = document.createElement("div");
                div.style.padding = "10px";
                div.style.borderRadius = "5px";
                div.style.marginBottom = "10px";
                div.style.backgroundColor = resultado.es_correcta ? "#d4edda" : "#f8d7da"; // Verde si es correcta, rojo si es incorrecta

                div.innerHTML = `
                    <p><strong>Pregunta:</strong> ${resultado.question ? resultado.question.description : 'Pregunta no disponible'}</p>
                    <p><strong>Tu respuesta:</strong> ${resultado.respuesta}</p>
                    ${!resultado.es_correcta ? `<p><strong>Respuesta correcta:</strong> ${resultado.question.correctAnswer ? resultado.question.correctAnswer.description : 'No disponible'}</p>` : ''}
                `;

                container.appendChild(div);
            });
        });
    </script>
@endsection
