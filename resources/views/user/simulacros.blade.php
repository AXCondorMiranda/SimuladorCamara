@php
    if(session('tipo') == null){
        if(auth()->user()->type_user){
            session()->flash('tipo', auth()->user()->type_user);
        }else{
            redirect()->away('/home')->send();
            exit;
        }
    }
@endphp

@extends('layouts.app')

@section('content')

    {{--<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Simulacros</h1>
            </div>
        </div>
        <form id="formSimulacros" method="POST" action="">
            @csrf
            <div class="row">
                <div class="col-md-12 text-center p-4 color-principal1">
                    <p>Realiza un simulacro de acuerdo a tus intereses</p>
                    <p>Selecciona uno</p>
                    <div class="d-flex justify-content-center">
                        <select name="type" class="form-select text-center" style="width: 60%;" id="simulacro_type">
                            <option value="opcion0">Seleccionar simulacro</option>
                            <option value="opcion1"><strong>{{ mb_strtoupper("SIMULADOR FORTALEZA", 'UTF-8') }}</strong></option>
                            <option value="opcion2"><strong>{{ mb_strtoupper("SIECOPOL PNP (Institucional)", 'UTF-8') }}</strong></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center p-4">
                    <ul class="list-unstyled">
                        <li>
                            <input class="form-check-input" value="50" type="radio" name="quantity" id="radioquestions1">
                            <label class="form-check-label" for="radioquestions1">
                                50 Preguntas
                            </label>
                        </li>
                        <li>
                            <input class="form-check-input" value="100" type="radio" name="quantity" id="radioquestions2">
                            <label class="form-check-label" for="radioquestions2">
                                100 Preguntas
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" onclick="ejecutarAccion()" style="width: 40%">Iniciar Simulacro</button>
                    </div>
                </div>
            </div>
        </form>


    </div>--}}

    <div class="portada">
        <div class="port_1" id="p_simulacros">
            <div class="port_1_container" id="simulacros">
                <h1>Simulacros</h1>
            </div>
        </div>
        <form action="{{route('generar.simulacro')}}" method="POST" id="form-simulacros">
            @csrf
            <div class="contenedor-practica-tema" id="contenedor_simulacros">
                <div class="tema_practicas_tema" name="grado" id="grado_practicas">
                    <div class="select_practicas_tema">
                        <span class="selected_practicas_tema">Seleccionar simulacros</span>
                        <div class="caret_practicas_tema"><img src="{{ asset('img/img_examen/icondwn.png') }}" alt="">
                        </div>
                    </div>
                    <ul class="menu_practicas_tema" name="simulacro_type">
                        <li value="1">Simulador FORTALEZA</li>
                        <li value="2">SIECOPOL PNP(Institucional)</li>
                    </ul>
                </div>
                <input type="hidden" id="simulacro_type_option" value="">
            </div>
            <div class="contenedor-examen">
                <div id="cant_preguntas" class="preguntas" style="visibility: hidden">
                    <input type="radio" name="quantity" id="pregunta50" value="50"> 50 preguntas
                    <input type="radio" name="quantity" id="pregunta100" value="100"> 100 preguntas
                </div>
            </div>
            <div class="boton_iniciar" id="boton_simulador">
                <button type="button" class="boton" id="botton_simulador" onclick="ejecutar_accion();">
                    Iniciar Simulacro
                </button>
            </div>
        </form>
        <a id="redirect_siecopol" href="https://siecopol.policia.gob.pe/simulador/login.aspx" target="_blank" style="display: none"></a>
    </div>

    <script>
        const dropdowns = document.querySelectorAll('.tema_practicas_tema');
        dropdowns.forEach(dropdown => {
            const select = dropdown.querySelector('.select_practicas_tema');
            const caret = dropdown.querySelector('.caret_practicas_tema');
            const menu = dropdown.querySelector('.menu_practicas_tema');
            const options = dropdown.querySelectorAll('.menu_practicas_tema li');
            const selected = dropdown.querySelector('.selected_practicas_tema');

            select.addEventListener('click', () => {
                select.classList.toggle('select-clicked_practicas_tema');
                caret.classList.toggle('caret-rotate_practicas_tema');
                menu.classList.toggle('menu-open_practicas_tema');
            });
            options.forEach(option => {
                option.addEventListener('click', () => {
                    selected.innerText = option.innerText;
                    select.classList.remove('select-clicked_practicas_tema');
                    caret.classList.remove('caret-rotate_practicas_tema');
                    menu.classList.remove('menu-open_practicas_tema');

                    option.forEach(option => {
                        option.classList.remove('active_practica');
                    });
                    option.classList.add('active_practica');
                });
            });
        });


        function ejecutar_accion() {
            var formulario = document.getElementById('form-simulacros');
            var value = document.getElementById('simulacro_type_option').value;

            if (value === "1") {
                if (document.getElementById('pregunta50').checked || document.getElementById('pregunta100').checked) {
                    formulario.submit();
                } else {
                    alert("Elija una opción");
                    formulario.preventDefault();
                }
                formulario.submit();
            } else if (value === "2") {
                document.getElementById('redirect_siecopol').click();
            } else {
                alert("Elija una opción");
                formulario.preventDefault();
            }
        }

        const menu_practicas_tema = document.querySelector('.menu_practicas_tema');
        menu_practicas_tema.addEventListener('click', function (e) {
            if (e.target.tagName === 'LI') {
                var value = e.target.getAttribute('value');
                document.getElementById('simulacro_type_option').value = value;

                if(value === "1") {
                    document.getElementById('cant_preguntas').style.visibility = 'visible';
                } else if(value === "2") {
                    document.getElementById('cant_preguntas').style.visibility = 'hidden';
                }
            }
        });
    </script>

@endsection
