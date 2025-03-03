@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="practicas-temas">
    <div class="practicas_tema_intro">
        <h1>Prácticas por temas</h1>
        <h3>Realiza tus prácticas con las <br>preguntas de tu preferencia</h3>
    </div>
    <form method="POST" action="{{route('generar.practica')}}">
        @csrf
        <div class="contenedor-practica-tema">
            <div class="tema_practicas_tema" name="grado" id="grado_practicas">
                <div class="select_practicas_tema">
                    <span class="selected_practicas_tema">Seleccione un tema</span>
                    <div class="caret_practicas_tema">
                        <img src="{{ asset('img/img_examen/icondwn.png') }}" alt="">
                    </div>
                </div>

                <ul class="menu_practicas_tema">
                    @if($tests->isEmpty())
                    <li class="text-danger">No hay temas disponibles</li>
                    @else
                    @foreach($tests as $test)
                    <li data-value="{{ $test->id }}">{{ strtoupper($test->name) }}</li>
                    @endforeach
                    @endif
                </ul>

            </div>
        </div>
        <input type="hidden" name="test_id" id="test_id" value="">
        <div class="contenedor-examen">
            <div class="preguntas">
                <label for="cantidad">Cantidad de preguntas:</label>
                <input type="number" name="quantity" id="cantidad" min="1" placeholder="Ingresa la cantidad de preguntas" required>
            </div>
            <div class="boton_iniciar">
                <button type="submit" id="boton-iniciar-practica" class="boton">Iniciar Práctica</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.querySelector('.tema_practicas_tema');
        if (!dropdown) {
            console.error("❌ Dropdown no encontrado");
            return;
        }

        const select = dropdown.querySelector('.select_practicas_tema');
        const caret = dropdown.querySelector('.caret_practicas_tema');
        const menu = dropdown.querySelector('.menu_practicas_tema');
        const options = dropdown.querySelectorAll('.menu_practicas_tema li');
        const selected = dropdown.querySelector('.selected_practicas_tema');
        const testIdInput = document.getElementById('test_id');

        if (!select || !caret || !menu || !selected || !testIdInput) {
            console.error("❌ Uno o más elementos del dropdown no encontrados");
            return;
        }

        console.log("📌 Dropdown detectado correctamente");

        // Abre o cierra el menú desplegable
        select.addEventListener('click', () => {
            select.classList.toggle('select-clicked_practicas_tema');
            caret.classList.toggle('caret-rotate_practicas_tema');
            menu.classList.toggle('menu-open_practicas_tema');
        });

        // Maneja la selección de opciones en el menú desplegable
        options.forEach(option => {
            option.addEventListener('click', () => {
                console.log("📌 Tema seleccionado:", option.innerText);
                selected.innerText = option.innerText;
                select.classList.remove('select-clicked_practicas_tema');
                caret.classList.remove('caret-rotate_practicas_tema');
                menu.classList.remove('menu-open_practicas_tema');

                // Remueve la clase activa de las otras opciones
                options.forEach(opt => opt.classList.remove('active_practica'));
                option.classList.add('active_practica');

                // Actualiza el input oculto con el test_id seleccionado
                testIdInput.value = option.getAttribute('data-value');
            });
        });

        // Manejo de envío con fetch()
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            let testId = testIdInput.value;
            let quantity = document.getElementById('cantidad').value;

            if (!testId) {
                alert("Por favor, selecciona un tema antes de continuar.");
                return;
            }

            if (!quantity || quantity <= 0) {
                alert("Por favor, ingresa una cantidad válida de preguntas.");
                return;
            }

            fetch('/generar-practica', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        test_id: testId,
                        quantity: quantity
                    })
                })
                .then(response => {
                    return response.text(); // Lee como texto para detectar si no es JSON
                })
                .then(data => {
                    try {
                        let jsonData = JSON.parse(data); // Intenta parsear como JSON
                        console.log('✅ Práctica generada:', jsonData);
                        if (jsonData.redirect) {
                            console.log('🔀 Redirigiendo a:', jsonData.redirect);
                            window.location.href = jsonData.redirect;
                        } else {
                            alert('⚠ No se recibió una URL de redirección válida.');
                        }
                    } catch (error) {
                        console.error('❌ Error al procesar JSON. Respuesta del servidor:', data);
                        alert('Error en el servidor. Revisa la consola.');
                    }
                })
                .catch(error => {
                    console.error('❌ Error al generar práctica:', error);
                    alert('Error al generar la práctica. Revisa la consola para más detalles.');
                });
        });
    });
</script>

@endsection