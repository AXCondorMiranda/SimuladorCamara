@extends('layouts.simple')

@section('content')

<body>
    <div class="container">
        <h2>Historial de Exámenes</h2>

        @if ($historialExamenes->isEmpty())
        <div class="alert alert-warning">No tienes exámenes registrados.</div>
        @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Test</th>
                    <th>Puntaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historialExamenes as $index => $examen)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $examen['fecha'] }}</td>  {{-- Acceder como array --}}
                    <td>{{ $examen['test'] }}</td>  {{-- Acceder como array --}}
                    <td>{{ $examen['puntaje'] }}</td>  {{-- Acceder como array --}}
                    <td>
                    <a href="{{ url('/examen/resultado', ['test_session_id' => $examen['session_id']]) }}" class="btn btn-info btn-sm">
                            Ver Detalle
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('inicio') }}" class="btn btn-primary">Regresar a Inicio</a>
        </div>
    </div>
</body>
@endsection
