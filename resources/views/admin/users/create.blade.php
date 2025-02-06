@extends('layouts.admin')

@section('content')

    <div class="container">
        <h1>Registrar Usuario</h1>
        <form action="{{route('usuario.store')}}" method="POST">
            @csrf
            <div class="mb-3 row">
                <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="exampleFormControlInput2" class="col-sm-2 col-form-label">Correo</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="exampleFormControlInput2" placeholder="">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Contraseña</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="inputPassword">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="exampleFormControlInput2" class="col-sm-2 col-form-label">Accesos</label>
                <div class="col-sm-5">
                    <input type="checkbox" class="form-check-input" name="web_access" id="web_access">
                    <label class="form-check-label" for="web_access">Web</label>
                </div>
                <div class="col-sm-5">
                    <input type="checkbox" class="form-check-input" name="mobile_access" id="mobile_access">
                    <label class="form-check-label" for="mobile_access">Móvil</label>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    </div>

@endsection
