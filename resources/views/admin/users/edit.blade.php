@extends('layouts.admin')

@section('content')

    <div class="container">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3>Editar Usuario</h3>
                    <form id="form_actualizar_usuario" method="POST" action="{{route('usuario.update', $user->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput2" class="col-sm-2 col-form-label">Correo</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" id="exampleFormControlInput2" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Contraseña</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" id="inputPassword" disabled>
                                <input type="checkbox" class="form-check-input" name="change_password" id="change_password">
                                <label class="form-check-label" for="change_password">Cambiar constraseña</label>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput2" class="col-sm-2 col-form-label">Accesos</label>
                            <div class="col-sm-5">
                                <input type="checkbox" class="form-check-input" name="web_access" id="web_access" {{ $user->web_access == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="web_access">Web</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="checkbox" class="form-check-input" name="mobile_access" id="mobile_access" {{ $user->mobile_access == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mobile_access">Móvil</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
