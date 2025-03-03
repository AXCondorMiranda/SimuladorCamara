@extends('layouts.simple')

@section('content')

<body data-session-id="{{ request('test_session_id') }}">
    <div class="container mt-5">
        <h2 class="text-center mb-4">📊 Resultados del Examen</h2>

        <!-- Resumen de resultados -->
        <div class="alert alert-info text-center">
            <h4><strong>Total Correctas:</strong> <span class="text-success">{{ $totalCorrectas }}</span></h4>
            <h4><strong>Total Incorrectas:</strong> <span class="text-danger">{{ $totalIncorrectas }}</span></h4>
        </div>

        <!-- Listado de respuestas -->
        @foreach($resultados as $resultado)
        <div class="card mb-3 shadow-lg" 
             style="border-left: 5px solid {{ $resultado->es_correcta ? '#28a745' : '#dc3545' }};">
            <div class="card-body">
                <p class="question">
                    <strong>❓ Pregunta:</strong> {{ $resultado->question->description ?? 'Pregunta no disponible' }}
                </p>
                <p class="user-answer">
                    <strong>✏️ Tu respuesta:</strong> {{ $resultado->respuesta ?? 'No disponible' }}
                </p>

                <!-- Mostrar respuesta correcta si la del usuario es incorrecta -->
                @if(!$resultado->es_correcta)
                <p class="correct-answer text-danger">
                    <strong>✅ Respuesta correcta:</strong> 
                    {{ $resultado->question->correctAnswer->description ?? 'No disponible' }}
                </p>
                @else
                <p class="text-success">
                    🎉 ¡Respuesta correcta!
                </p>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Botón para regresar al inicio -->
        <div class="text-center mt-4">
            <a href="{{ route('inicio') }}" class="btn btn-primary btn-lg">
                🔙 Regresar al Inicio
            </a>
        </div>
    </div>
</body>

<style>
    /* Estilos generales */
    .result-container {
        max-width: 600px;
        margin: auto;
        font-family: Arial, sans-serif;
    }

    .result-summary {
        background-color: #e3f2fd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .correct-answer {
        border: 2px solid #28a745;
        background-color: #eaf8ea;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .incorrect-answer {
        border: 2px solid #dc3545;
        background-color: #fdecea;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .question {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .user-answer {
        font-weight: bold;
        color: #000;
    }

    .correct-indicator {
        color: #28a745;
        font-weight: bold;
    }

    .incorrect-indicator {
        color: #dc3545;
        font-weight: bold;
    }
</style>

@endsection