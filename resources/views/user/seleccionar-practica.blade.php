@extends('layouts.app')

@section('content')

<div class="practicas-temas">
    <div class="practicas_tema_intro">
        <h1>Prácticas por temas </h1>
        <h3>Realiza tus practicas con las <br>preguntas de tu preferencia </h3>
    </div>
    <form method="POST" action="{{route('generar.practica')}}">
        @csrf
        <div class="contenedor-practica-tema">
            <div class="tema_practicas_tema" name="grado" id="grado_practicas">
                <div class="select_practicas_tema">
                    <span class="selected_practicas_tema">Seleccione un tema</span>
                    <div class="caret_practicas_tema"><img src="{{ asset('img/img_examen/icondwn.png') }}" alt=""></div>
                </div>
                <ul class="menu_practicas_tema">
                    @foreach($tests as $test)
                    <li value="{{ $test->id }}">{{ mb_strtoupper($test->name, 'UTF-8') }}</li>
                    @endforeach
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
                <button type="submit" class="boton">Iniciar Práctica</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('.tema_practicas_tema');
    const testIdInput = document.getElementById('test_id'); // Input oculto para almacenar el test_id seleccionado

    dropdowns.forEach(dropdown => {
        const select = dropdown.querySelector('.select_practicas_tema');
        const caret = dropdown.querySelector('.caret_practicas_tema');
        const menu = dropdown.querySelector('.menu_practicas_tema');
        const options = dropdown.querySelectorAll('.menu_practicas_tema li');
        const selected = dropdown.querySelector('.selected_practicas_tema');

        // Abre o cierra el menú desplegable
        select.addEventListener('click', () => {
            select.classList.toggle('select-clicked_practicas_tema');
            caret.classList.toggle('caret-rotate_practicas_tema');
            menu.classList.toggle('menu-open_practicas_tema');
        });

        // Maneja la selección de opciones en el menú desplegable
        options.forEach(option => {
            option.addEventListener('click', () => {
                // Actualiza el texto del elemento seleccionado
                selected.innerText = option.innerText;

                // Cierra el menú desplegable
                select.classList.remove('select-clicked_practicas_tema');
                caret.classList.remove('caret-rotate_practicas_tema');
                menu.classList.remove('menu-open_practicas_tema');

                // Remueve la clase activa de las otras opciones
                options.forEach(opt => {
                    opt.classList.remove('active_practica');
                });

                // Añade la clase activa a la opción seleccionada
                option.classList.add('active_practica');

                // Actualiza el valor del input oculto con el test_id seleccionado
                testIdInput.value = option.getAttribute('value');
            });
        });
    });

    // Validación adicional antes de enviar el formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const cantidadInput = document.getElementById('cantidad'); // Input de cantidad
        const cantidad = parseInt(cantidadInput.value);

        if (!testIdInput.value) {
            e.preventDefault();
            alert('Por favor, selecciona un tema antes de continuar.');
            return;
        }

        if (isNaN(cantidad) || cantidad <= 0) {
            e.preventDefault();
            alert('Por favor, ingresa un número válido de preguntas.');
        }
    });
});

</script>

@endsection