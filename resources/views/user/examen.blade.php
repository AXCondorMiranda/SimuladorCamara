@extends('layouts.examen-base')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<body data-test-id="{{ session('test_id', 'no-id') }}" data-session-id="{{ session('test_session_id', 'no-session') }}">
    <div class="row m-2">
        <div class="col-md-2 my-3">
            <div class="card">
                <div class="card-body">
                    <div class="list-container">
                        {{-- Aqui se listaran los numeros de las preguntas --}}
                        <ul class="list-unstyled" id="listQuestions"></ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10 my-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-1 my-1">
                                    {{-- Contador de los minutos --}}
                                    <div id="countdown"></div>
                                </div>
                                <div class="col-md-8 my-1" id="tituloexamen"></div>
                                <div id="preguntasListado"></div>
                                <div class="col-md-3 my-1">
                                    <button type="button" class="btn btn-light float-end" data-bs-toggle="modal"
                                        data-bs-target="#finalizarExamenModal">
                                        Finalizar examen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-md-6">
                            <p id="correctCounter" class="text-success">Correctas: 0</p>
                        </div>
                        <div class="col-md-6">
                            <p id="incorrectCounter" class="text-danger">Incorrectas: 0</p>
                        </div>
                    </div>

                    <hr class="mt-1 mb-1 text-primary" />
                    <form id="formGuardarExamen" action="{{ route('guardar.respuestas') }}" method="POST">
                        @csrf
                        <input type="hidden" name="respuestasExamen" id="respuestasExamenInput">
                        <input type="hidden" name="test_id" value="{{ session('test_id') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para finalizar examen -->
    <div class="modal fade" id="finalizarExamenModal" tabindex="-1" aria-labelledby="finalizarExamenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalizarExamenModalLabel">Finalizar Examen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿CONFIRMA SU DECISIÓN DE FINALIZAR SU EXAMEN VIRTUAL?
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitExam" class="btn btn-primary">Sí</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>



    <form id="formGuardarExamen" action="{{ route('guardar.respuestas') }}" method="POST">
        @csrf
        <input type="hidden" name="respuestasExamen" id="respuestasExamenInput">
    </form>

    <div class="modal fade" id="finalizarExamenModal" tabindex="-1" aria-labelledby="finalizarExamenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5">
                <div class="modal-body text-center">
                    <p>EL PRESENTE PROCEDIMIENTO DARÁ POR CONCLUIDO SU EXAMEN VIRTUAL</p>
                    <p>¿ CONFIRMA SU DECISIÓN DE FINALIZAR SU EXAMEN VIRTUAL ?</p>
                    <div class="d-grid gap-2 col-12 mx-auto">
                        <button type="button" id="btnSubmitExam" class="btn btn-primary">Sí</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let finalizarExamenModal = document.getElementById("finalizarExamenModal");

        if (finalizarExamenModal) {
            let modalInstance = new bootstrap.Modal(finalizarExamenModal);
            finalizarExamenModal.addEventListener("shown.bs.modal", function() {
                console.log("📌 Modal abierto correctamente.");
            });
        } else {
            console.error("❌ No se encontró el modal 'finalizarExamenModal'. Verifica el HTML.");
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let testId = document.body.getAttribute("data-test-id");
        let testSessionId = document.body.getAttribute("data-session-id");

        console.log("📌 Test ID desde HTML:", testId);
        console.log("Test Session ID obtenido:", testSessionId);

        if (!testId) {
            console.error("❌ testId no está presente en el HTML");
        }

    });
</script>
<script>
    let resultados = @json($resultados ?? []);
    console.log("📌 Datos de resultados:", resultados);
</script>
<script>
     let listaPreguntas = @json($preguntas ?? []);
     console.log("📌 Preguntas recibidas en la vista:", listaPreguntas);

    if (!Array.isArray(listaPreguntas) || listaPreguntas.length === 0) {
        console.error("❌ No se recibieron preguntas en la vista. Verifica la base de datos.");
    }
    listaPreguntas.forEach(pregunta => {
        console.log("📌 Pregunta cargada:", pregunta.descripcion);
    });
    
    let test = @json($test);
</script>

<script src="{{ asset('js/ExamenFunciones.js') }}"></script>

<style>
    .list-container {
        column-count: 4;
    }

    .list-item {
        list-style: none;
    }

    .respondida {
        background-color: #d4edda;
        /* Verde claro */
        border-radius: 5px;
    }

    .pregunta-activa {
        background-color: #c3e6cb !important;
        /* Verde más oscuro */
        font-weight: bold;
    }
</style>
@endsection