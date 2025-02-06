@extends('layouts.admin')

@section('content')

    @php
        // dd($tests);
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-6 mt-4">
                <h1 class="ml-4">Usuarios</h1>
            </div>
            <div class="col-6 mt-4 text-end mr-4">
                <a href="{{ route('usuario.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Nuevo usuario
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
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol</th>
                            <th scope="col">¿Acceso Web?</th>
                            <th scope="col">¿Acceso Móvil?</th>
                            <th scope="col">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                @if($user->web_access == 1)
                                    <td>SI</td>
                                @else
                                    <td>NO</td>
                                @endif
                                @if($user->mobile_access == 1)
                                    <td>SI</td>
                                @else
                                    <td>NO</td>
                                @endif
                                <td>
                                    <a href="{{ route('usuario.edit', $user->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
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
