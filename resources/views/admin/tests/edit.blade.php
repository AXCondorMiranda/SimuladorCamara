@extends('layouts.admin')

@section('content')

<div class="container">
     <div class="container mt-5">
          <div class="row justify-content-center">
              <div class="col-md-6">
                  <h3>Editar Examen</h3>
                  <form id="form_actualizar_examen" method="POST" action="{{ route('examen.update', $test->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                         <label for="exam_type" class="form-label">Tipo de examen</label>
                         <select name="test_type_id" class="form-select" id="exam_type">
                              <option value="">Selecciona un tipo de examen</option>
                              @foreach($test_types as $test_type)
                                  <option value="{{ $test_type->id }}" {{ $test_type->id == $test->test_type_id ? 'selected' : '' }}>
                                      {{ $test_type->name }}
                                  </option>
                              @endforeach
                          </select>
                    </div>
                    <div class="mb-3">
                         <label for="name" class="form-label">Descripción</label>
                         <input type="text" class="form-control" name="name" id="name" value="{{ $test->name }}">
                    </div>
                    <div class="mb-3">
                         <label for="quantity" class="form-label">Cantidad de preguntas</label>
                         <input type="text" class="form-control" name="quantity" id="quantity" value="{{ $test->quantity }}">
                    </div>
                    <div class="mb-3">
                         <label for="state" class="form-label">Estado</label>
                         <div class="form-check">
                              <input type="checkbox" class="form-check-input" name="state" id="state" {{ $test->state ? 'checked' : '' }}>
                              <label class="form-check-label" for="state">Estado</label>
                         </div>
                    </div>
                    <div class="d-flex justify-content-between">
                         <a href="{{ route('examen.index') }}" class="btn btn-secondary" style="flex-basis: 50%;">Cancelar</a>
                         <button id="btnSubmitEditExamen" type="submit" class="btn btn-primary" style="flex-basis: 50%;">Guardar</button>
                        <input type="hidden" id="questionsCount" value="{{ $questions }}" />
                     </div>
                  </form>
              </div>
          </div>
     </div>
</div>

@endsection
