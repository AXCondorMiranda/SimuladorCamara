<nav>
    <div>
        <div class="top">

            <div class="top_img">
                <img src="{{ asset('img/img_botones/Logo.png') }}" alt="">
            </div>
            <div class="top_nav">
                <div id="iconos">
                    <a href="{{url('/inicio')}}"><img src="{{ asset('img/img_examen/icon_inicio.png') }}" alt="">
                        <h4>Inicio</h4>
                    </a>
                </div>
                <div class="top_b_izq" id="iconos">
                    <a href="{{route('practica.tema')}}"> <img src="{{ asset('img/img_examen/icon_practicas.png') }}" alt="">
                        <h4>Practicas por tema</h4>
                    </a>
                </div>
                <div class="top_b_izq" id="iconos">
                    <a href="{{route('temario')}}"><img src="{{ asset('img/img_examen/icon_temario.png') }}" alt="">
                        <h4>Temario</h4>
                    </a>
                </div>
                <div class="top_b_izq" id="iconos">
                    <a href="{{ route('simulacros') }}"><img src="{{ asset('img/img_examen/icon_simulador.png') }}" alt="">
                        <h4>Simulacros</h4>
                    </a>
                </div>
            </div>
            <div class="top_usuario">
                <div class="drop">
                    <button class="dropdown-button"><img src="{{ asset('img/img_examen/icondwn.png') }}" alt="icondwn"></button>
                    <div class="dropdown-content-usuario">
                        <div class="content_drop">
                            <div class="content_drop_circle">PS</div>
                            <h3>{{ auth()->user()->name }}</h3>
                            <h4>{{ auth()->user()->email }}</h4>
                            <div class="puntaje_container">
                                <a href="{{route('historial.puntajes')}}">
                                    <button id="puntaje">
                                        <h2>Puntajes</h2>
                                    </button>
                                </a>
                            </div>
                            <div class="cuadro_drop">
                                <a href="{{route('logout')}}">CERRAR SESIÓN</a>
                            </div>
                            <div class="linea_sesion"><img src="{{ asset('img/img_examen/lineasesion.png') }}" alt="">
                            </div>
                            <div class="content_drop_asesor">
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=902742435" rel="noopener noreferrer">
                                    Chatea con un asesor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownButton = document.querySelector('.dropdown-button');
        var dropdownContent = document.querySelector('.dropdown-content-usuario');
        var overlay = document.querySelector('.overlay');

        dropdownButton.addEventListener('click', function() {
            dropdownContent.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        // Opcional: Cierra el dropdown si se hace clic fuera de él
        window.addEventListener('click', function(e) {
            if (!dropdownButton.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });
</script>
