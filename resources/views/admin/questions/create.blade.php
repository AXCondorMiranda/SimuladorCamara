@extends('layouts.admin')

@section('content')

    <div class="container p-3">
        <div class="row">
            <div class="col-md-12">
                <label for="formSelectExam" class="form-label">Examen-Practica</label>
                <div class="d-flex">
                    <select class="form-select" id="test_id" name="test_id" aria-label="Default select example">
                        <option>Seleccionar</option>
                        @foreach ($tests as $test)
                            <option value="{{ $test->id }}">{{ $test->name }}</option>
                        @endforeach
                    </select>
                    <button id="addQuestions" type="button" class="btn btn-primary btn-sm ms-2">Configurar Preguntas</button>
                </div>
            </div>
        </div>
        <form id="form_question" method="POST"  action="{{route('preguntas.store')}}">
            @csrf
            <div class="row" id="questions-container">
            </div>
            <div class="d-flex justify-content-between" id="btnSubmitCancel">
           </div>
        </form>

    </div>


@endsection
