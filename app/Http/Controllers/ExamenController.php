<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\RespuestaUsuario;
use Illuminate\Http\Request;
use App\Models\ResultadoExamen;
use Illuminate\Support\Facades\Log;

class ExamenController extends Controller
{
    /**
     * Inicia un nuevo examen basado en el tipo de usuario.
     */
    public function iniciarExamen()
    {
        try {
            Log::info('Iniciar examen ejecutado', ['user_id' => auth()->user()->id]);

            $tipo = auth()->user()->affiliate_type_id;

            if (!$tipo) {
                return redirect()->route('seleccionar.tipo')->with('error', 'Seleccione su tipo de usuario.');
            }

            $test = Test::where('test_type_id', $tipo)
                ->where('state', true)
                ->where('is_practice', false)
                ->latest()
                ->first();

            if (!$test) {
                return redirect()->route('inicio')->with('error', 'No hay exámenes disponibles.');
            }

            $questions = $test->questions;
            if ($questions->isEmpty()) {
                return redirect()->route('inicio')->with('error', 'El examen no tiene preguntas.');
            }

            session(['test_id' => $test->id]);

            $listaPreguntas = $questions->shuffle()->take(min(100, $questions->count()))->map(function ($question) {
                return [
                    'id' => $question->id,
                    'nombre' => $question->description,
                    'respuestas' => $question->alternatives->map(fn($alt) => [
                        'id' => $alt->id,
                        'respuesta' => $alt->description,
                        'is_correct' => $alt->is_correct,
                    ])->toArray(),
                ];
            })->toArray();

            return view('user.examen', compact('test', 'listaPreguntas'));
        } catch (\Exception $e) {
            Log::error('Error al iniciar examen: ' . $e->getMessage());
            return redirect()->route('inicio')->with('error', 'Error inesperado.');
        }
    }


    /**
     * Guarda las respuestas del usuario en la base de datos.
     */
    public function guardarRespuestas(Request $request) {
        Log::info('Recibiendo respuestas:', $request->all());
    
        if (!$request->has('respuestasExamen')) {
            return response()->json(['error' => 'No se enviaron respuestas.'], 400);
        }
    
        return response()->json(['success' => 'Respuestas guardadas correctamente.']);
    }
    



    /**
     * Muestra el resumen del examen con respuestas correctas e incorrectas.
     */
    public function resultado()
    {
        $userId = auth()->id();
        $testId = session('test_id');

        if (!$testId) {
            return redirect()->route('inicio')->with('error', 'No hay examen en proceso.');
        }

        $resultados = ResultadoExamen::where('user_id', $userId)->where('test_id', $testId)->get();

        if ($resultados->isEmpty()) {
            return redirect()->route('inicio')->with('error', 'No hay respuestas registradas.');
        }

        return view('user.examen-resultado', [
            'listCorrects' => $resultados->where('es_correcta', 1),
            'listIncorrects' => $resultados->where('es_correcta', 0),
            'result' => [
                'total_correct' => $resultados->where('es_correcta', 1)->count(),
                'total_incorrect' => $resultados->where('es_correcta', 0)->count(),
            ]
        ]);
    }
}
