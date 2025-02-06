<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ASOCALEF</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link type="text/css" href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- CDNs de Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .navbar{
            background: linear-gradient(to right, #00416A 0%, #0060AB 50%, #00D3B9 100%);
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            /* border-radius: 20%; */
        }

    </style>

</head>
<body>
    <main>
        @include('layouts.admin_navbar')
    </main>
    @yield('content')

    <script>
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        function handleChange(checkbox) {
            if (checkbox.checked) {
                checkbox.value = "true";
            } else {
                checkbox.value = "false";
            }
        }

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                const testId = button.getAttribute('data-id');
                Swal.fire({
                    title: '¿Estás seguro de eliminar?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`idEliminar${testId}`);
                        if (form) {
                            form.submit();
                        } else {
                            console.error('No se encontró el formulario');
                        }
                    }
                });
            });
        });

        $("#btnSubmitEditExamen").on("click", function (e) {
            let cantidad = $("#quantity").val();
            let preguntas = $("#questionsCount").val();
            cantidad = parseInt(cantidad);
            preguntas = parseInt(preguntas);

            if (isNaN(cantidad)) {
                e.preventDefault();
                alert("La cantidad de preguntas debe ser un número");
                return;
            }

            if (cantidad < preguntas) {
                e.preventDefault();
                alert("La cantidad de preguntas no puede ser menor a las preguntas actuales");
                return;
            }

            if (cantidad <= 0) {
                e.preventDefault();
                alert("La cantidad de preguntas debe ser mayor a 0");
            }


        });

        $("#btnSubmitEditPractice").on("click", function (e) {
            let cantidad = $("#quantity").val();
            let preguntas = $("#questionsCount").val();
            cantidad = parseInt(cantidad);
            preguntas = parseInt(preguntas);

            if (isNaN(cantidad)) {
                e.preventDefault();
                alert("La cantidad de preguntas debe ser un número");
                return;
            }

            if (cantidad < preguntas) {
                e.preventDefault();
                alert("La cantidad de preguntas no puede ser menor a las preguntas actuales");
                return;
            }

            if (cantidad <= 0) {
                e.preventDefault();
                alert("La cantidad de preguntas debe ser mayor a 0");
            }


        });

        $("#change_password").on("change", function () {
            if (this.checked) {
                $("#inputPassword").removeAttr("disabled");
            } else {
                $("#inputPassword").attr("disabled", "disabled");
            }
        });

        document.getElementById("addQuestions").addEventListener("click", function(){

            const selectElement = document.getElementById("test_id");
            const selectedValue = selectElement.value;

            $.ajax({
                url: "{{ route('buscar.examen', ['id' => ':id']) }}".replace(':id', selectedValue),
                method: 'GET',
                success: function(response){
                    let test=response.data
                    let c = 0;

                    $('#questions-container').empty();
                    $('#btnSubmitCancel').empty();

                    $.each(test.questions, function (i, question) {
                        let p = i + 1;
                        let preguntaHtml = `
                            <div class="col-md-12">
                            <div class="card m-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="formQuestion" class="form-label">Pregunta ${p}</label>
                                            <input type="hidden" class="form-control" name="P${p}DB"  value="${question.id}">
                                            <input type="text" class="form-control" required name="P${p}" value="${question.description.replace(/"/g, '&quot;')}">
                                        </div>
                                        <hr class="mt-2">
                                    </div>
                                </div>
                                <div class="row m-1" id="answer-container">
                                    <div class="col-md-12">
                                        <ul class="list-unstyled">`;

                        $.each(question.alternatives, function (j, alternative) {
                            let a = j + 1;

                            preguntaHtml += `
                            <li>
                                <div class="row">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="radionameP${p}${a}" id="radio${a}" ${alternative.is_correct === 1 ? 'checked' : ''} >
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">A</span>
                                            <input type="hidden" required name="P${p}A${a}DB" class="form-control" value="${alternative.id}">
                                            <input type="text" required name="P${p}A${a}" class="form-control" value="${alternative.description.replace(/"/g, '&quot;')}" aria-label="Username" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                            </li>`;
                        });

                        preguntaHtml += `
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                        c++;
                        $('#questions-container').append(preguntaHtml);
                    });

                    let idExamen=test.id
                    let quantity=test.quantity
                    quantity = quantity - c;

                    for(let i=1; i<=quantity;i++){
                        let x = c + 1;
                        let preguntaHtml = `
                            <div class="col-md-12">
                            <div class="card m-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="formQuestion" class="form-label">Pregunta ${x}</label>
                                            <input type="hidden" class="form-control" name="P${x}DB"  value="0">
                                            <input type="text" class="form-control" required name="P${x}" placeholder="Colocar pregunta">
                                        </div>
                                        <hr class="mt-2">
                                    </div>
                                </div>
                                <div class="row m-1" id="answer-container">
                                    <div class="col-md-12">
                                        <ul class="list-unstyled">
                                            <li>
                                                <div class="row">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="radionameP${x}1" id="radio${x}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">A</span>
                                                            <input type="hidden" required name="P${x}A1DB" class="form-control" value="0">
                                                            <input type="text" required name="P${x}A1" class="form-control" placeholder="Respuesta A" aria-label="Username" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="radionameP${x}2" id="radio${x}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">B</span>
                                                            <input type="hidden" required name="P${x}A2DB" class="form-control" value="0">
                                                            <input type="text" required name="P${x}A2" class="form-control" placeholder="Respuesta B" aria-label="Username" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="radionameP${x}3" id="radio${x}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">C</span>
                                                            <input type="hidden" required name="P${x}A3DB" class="form-control" value="0">
                                                            <input type="text" required name="P${x}A3" class="form-control" placeholder="Respuesta C" aria-label="Username" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="radionameP${x}4" id="radio${x}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">D</span>
                                                            <input type="hidden" required name="P${x}A4DB" class="form-control" value="0">
                                                            <input type="text" required name="P${x}A4" class="form-control" placeholder="Respuesta D" aria-label="Username" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="radionameP${x}5" id="radio${x}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">E</span>
                                                            <input type="hidden" required name="P${x}A5DB" class="form-control" value="0">
                                                            <input type="text" required name="P${x}A5" class="form-control" placeholder="Respuesta E" aria-label="Username" aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                        c++;
                        $('#questions-container').append(preguntaHtml);
                    }

                    let idinput = `
                        <input type="hidden" name="idExam" value="${idExamen}" class="form-control" aria-describedby="basic-addon1">
                        `;
                    $('#questions-container').append(idinput);
                    let btnSubmitCancel = `
                                <a href="{{ route('examen.index') }}" class="btn btn-secondary" style="flex-basis: 50%;">Cancelar</a>
                                <button type="submit" class="btn btn-primary" style="flex-basis: 50%;">Guardar</button>
                        `;
                    $('#btnSubmitCancel').append(btnSubmitCancel);
                },
                error: function(error){
                    console.log(error);
                }
            });

        });

        document.getElementById('form_question').addEventListener('submit', function (evento) {
            const contenedoresRespuestas = document.querySelectorAll('#answer-container');

            contenedoresRespuestas.forEach((contenedor, index) => {
                const elementosUL = contenedor.querySelectorAll('ul');

                let alMenosUnCheckboxSeleccionado = false;
                elementosUL.forEach(ul => {
                    const elementosCheck = ul.querySelectorAll('input');

                    elementosCheck.forEach(check => {
                        if (check.checked) {
                            alMenosUnCheckboxSeleccionado = true;
                        }
                    });

                });
                if (!alMenosUnCheckboxSeleccionado) {
                    evento.preventDefault();
                    alert(`Seleccionar una opción para la pregunta ${index+1}.`);
                    return;
                }

            });

        });

    </script>
</body>

</html>
