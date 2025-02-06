@extends('layouts.admin')

@section('content')

@php
    // dd($tests);
@endphp
<div class="container">
    <div class="row">
        <div class="col-6 mt-4">
            <h1 class="ml-4">Prácticas</h1>
        </div>
        <div class="col-6 mt-4 text-end mr-4">
            <a href="{{ route('practica.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Nueva práctica
            </a>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Tema</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Cantidad Preguntas</th>
                            <th scope="col">¿Preguntas Registradas?</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tests as $test)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $test->test_type->name }}</td>
                            <td>{{ $test->name }}</td>
                            <td>{{ $test->state ? 'Activo' : 'Inactivo' }}</td>
                            <td>{{ $test->quantity }}</td>
                            <td>{{ ($test->questions()->count() == $test->quantity)? 'Si' : 'No' }}</td>
                            <td>
                                <a href="{{ route('examen.edit', $test->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                <form id="idEliminar{{$test->id}}" action="{{ route('examen.destroy', $test->id) }}" method="POST" style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger delete-btn" data-id="{{ $test->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
