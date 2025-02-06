@extends('layouts.admin')

@section('content')
    
<div class="container">
     <div class="container mt-5">
          <div class="row justify-content-center">
              <div class="col-md-6">
                  <h3>Nuevo Examen</h3>
                  <form method="POST"  action="{{route('examen.store')}}">
                    @csrf
                    <div class="mb-3">
                         <label for="exam_type" class="form-label">Tipo de examen</label>
                         <select name="test_type_id" class="form-select" id="exam_type">
                              <option value="">Selecciona un tipo de examen</option>
                              @foreach($test_types as $test_type)
                                  <option value="{{ $test_type->id }}">{{ $test_type->name }}</option>
                              @endforeach
                          </select>
                    </div>
                    <div class="mb-3">
                         <label for="name" class="form-label">Descripción</label>
                         <input type="text"  class="form-control" name="name" id="name">
                    </div>
                    <div class="mb-3">
                         <label for="quantity" class="form-label">Cantidad de preguntas</label>
                         <input type="text"  class="form-control" name="quantity" id="quantity">
                    </div>
                    <div class="d-flex justify-content-between">
                         <a href="{{ route('examen.index') }}" class="btn btn-secondary" style="flex-basis: 50%;">Cancelar</a>
                         <button type="submit" class="btn btn-primary" style="flex-basis: 50%;">Guardar</button>
                     </div>
                  </form>
              </div>
          </div>
     </div>
</div>

@endsection