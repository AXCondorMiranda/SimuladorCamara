<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Tipo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container_principal">
    <div class="container_carrousel">
        <img src="{{ asset('img/img_botones/logo_blanco.png') }}" alt="Logo">
    </div>
    <div class="container_botones">
        <div class="afiliar">
            <h3>Hola {{ auth()->user()->name }}</h3>
        </div>
        <div class="bienvenida">
            <div class="bienvenida_text">
                <h1>Te damos la bienvenida</h1>
                <h2>Por favor selecciona tu tipo de usuario</h2>
            </div>
        </div>

        <div class="botones_landing">
            <!-- Opciones para Oficiales -->
            <div class="dropdown_landing">
                <div class="select_landing">
                    <span class="selected_landing">OFICIALES</span>
                    <div class="caret_landing"></div>
                </div>
                <ul class="menu_landing">
                    <li>
                        <a href="{{ route('usuario.tipo', 1) }}">Oficiales Superiores</a>
                    </li>
                    <li>
                        <a href="{{ route('usuario.tipo', 2) }}">Oficiales Subalternos</a>
                    </li>
                </ul>
            </div>

            <!-- Opciones para Suboficiales -->
            <div class="dropdown_landing">
                <div class="select_landing">
                    <span class="selected_landing">SUBOFICIALES</span>
                    <div class="caret_landing"></div>
                </div>
                <ul class="menu_landing">
                    <li>
                        <a href="{{ route('usuario.tipo', 4) }}">Suboficiales de Armas</a>
                    </li>
                    <li>
                        <a href="{{ route('usuario.tipo', 3) }}">Suboficiales de Servicios</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Botón de cierre de sesión -->
        <div class="cerrar_sesion">
            <button>
                <h1>
                    <a href="{{ route('logout') }}">CERRAR SESIÓN</a>
                </h1>
                <img src="{{ asset('img/img_botones/Salida.png') }}" alt="">
            </button>
        </div>
    </div>
</div>

<script>
    // Control de desplegables
    const dropdowns = document.querySelectorAll('.dropdown_landing');
    dropdowns.forEach(dropdown => {
        const select = dropdown.querySelector('.select_landing');
        const caret = dropdown.querySelector('.caret_landing');
        const menu = dropdown.querySelector('.menu_landing');
        const options = dropdown.querySelectorAll('.menu_landing li');
        const selected = dropdown.querySelector('.selected_landing');

        select.addEventListener('click', () => {
            select.classList.toggle('select-clicked_landing');
            caret.classList.toggle('caret-rotate_landing');
            menu.classList.toggle('menu-open_landing');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                selected.innerText = option.innerText;
                select.classList.remove('select-clicked_landing');
                caret.classList.remove('caret-rotate_landing');
                menu.classList.remove('menu-open_landing');

                options.forEach(option => {
                    option.classList.remove('active');
                });
                option.classList.add('active');
            });
        });
    });
</script>
</body>
</html>
