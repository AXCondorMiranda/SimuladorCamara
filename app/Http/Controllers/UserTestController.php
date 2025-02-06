<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Question;
use App\Models\Test;
use App\Models\Result;
use App\Models\ResultDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserTestController extends Controller
{
    /**
     * Generar un examen basado en el tipo de usuario.
     */
    public function iniciarExamen()
    {
        try {
            Log::info('Método iniciarExamen llamado', ['user_id' => auth()->user()->id]);

            $user = auth()->user();
            $tipo = $user->affiliate_type_id;

            if (!$tipo) {
                Log::warning('Tipo de usuario no definido', ['user_id' => $user->id]);
                return redirect()->route('seleccionar.tipo')->with('error', 'Por favor selecciona tu tipo de usuario.');
            }

            Log::info('Usuario con tipo válido', ['tipo' => $tipo]);

            // Obtener todos los exámenes activos de este tipo de usuario
            $tests = Test::whereIn('test_type_id', [$tipo])
                ->where('state', true)
                ->where('is_practice', true) // Aquí corregimos para incluir solo exámenes válidos
                ->get();

            Log::info('Exámenes obtenidos para este usuario', ['tests' => $tests->toArray()]);

            if ($tests->isEmpty()) {
                Log::warning('No se encontraron exámenes para este tipo de usuario', ['test_type_id' => $tipo]);
                return redirect()->route('inicio')->with('error', 'No hay exámenes disponibles.');
            }

            Log::info('Exámenes encontrados', ['count' => $tests->count()]);

            // Obtener preguntas de todos los exámenes encontrados
            $preguntasSeleccionadas = collect();
            $cantidadTotal = 100;

            foreach ($tests as $test) {
                $totalPreguntas = $test->questions->count();
                Log::info("Preguntas disponibles en test_id {$test->id}: {$totalPreguntas}");

                if ($totalPreguntas > 0) {
                    $preguntasPorTest = $test->questions()
                        ->inRandomOrder()
                        ->take(floor($cantidadTotal / max(1, $tests->count()))) // Evitar división por 0
                        ->get();

                    $preguntasSeleccionadas = $preguntasSeleccionadas->merge($preguntasPorTest);
                }
            }
            Log::info('Preguntas seleccionadas antes de creación', ['count' => $preguntasSeleccionadas->count()]);
            if ($preguntasSeleccionadas->isEmpty()) {
                Log::error('No se seleccionaron preguntas para el examen generado');
                return redirect()->route('inicio')->with('error', 'No hay preguntas disponibles.');
            }
            // Crear el simulacro aleatorio como un nuevo Test
            $simulacro = Test::create([
                'test_type_id' => 5, // Simulacros Aleatorios
                'name' => "Simulacro Aleatorio - " . now()->format('d-m-Y H:i'),
                'quantity' => $preguntasSeleccionadas->count(),
                'is_practice' => false,
                'state' => true,
            ]);

            Log::info('Simulacro creado', ['test_id' => $simulacro->id]);

            // Registrar el examen en la tabla results
            $result = Result::create([
                'user_id' => $user->id,
                'test_id' => $simulacro->id,
                'questions_total' => $preguntasSeleccionadas->count(),
                'duration' => now(),
                'total_marked' => 0,
                'total_correct' => 0,
                'total_incorrect' => 0,
            ]);

            Log::info('Examen registrado en la tabla results', ['result_id' => $result->id]);

            // Guardar preguntas en result_details
            foreach ($preguntasSeleccionadas as $pregunta) {
                ResultDetail::create([
                    'result_id' => $result->id,
                    'question_id' => $pregunta->id,
                    'user_answers' => null,
                ]);
            }

            Log::info('Detalles del examen creados en result_details');
            Log::info('Preguntas enviadas a la vista', ['preguntas' => $preguntasSeleccionadas->toArray()]);
            // Redirigir al examen
            return view('user.examen', [
                'test' => $tests->first(),
                'listaPreguntas' => $preguntasSeleccionadas->map(function ($pregunta) {
                    return [
                        'id' => $pregunta->id,
                        'test_id' => $pregunta->test_id,
                        'description' => $pregunta->description,
                        'state' => $pregunta->state,
                        'respuestas' => $pregunta->alternatives, // Asegúrate de incluir las respuestas
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            Log::error('Error en iniciarExamen', [
                'user_id' => auth()->user()->id ?? 'no-auth',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('inicio')->with('error', 'Ocurrió un error inesperado.');
        }
    }





    /**
     * Mostrar las prácticas disponibles agrupadas por tema.
     */
    public function practicaTema()
    {
        try {
            // Obtener las prácticas activas agrupadas por tema
            $tests = Test::where('state', true)
                ->where('is_practice', true) // Solo prácticas
                ->has('questions') // Validar que tenga preguntas asociadas
                ->get();

            return view('user.seleccionar-practica', compact('tests'));
        } catch (\Exception $e) {
            Log::error('Error al cargar las prácticas por tema: ' . $e->getMessage());
            return redirect()->route('inicio')->with('error', 'Hubo un problema al cargar las prácticas por tema.');
        }
    }



    /**
     * Generar una práctica personalizada basada en las preguntas de un test.
     */
    public function generarPractica(Request $request)
{
    $validatedData = $request->validate([
        'test_id' => 'required|exists:tests,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $test = Test::findOrFail($validatedData['test_id']);

    // Verificar que el test tenga preguntas
    $questions = $test->questions;
    if ($questions->isEmpty()) {
        return redirect()->route('practica.index')->with('error', 'El test seleccionado no tiene preguntas disponibles.');
    }

    // Ajustar la cantidad solicitada si excede el total de preguntas
    $cantidadSolicitada = $validatedData['quantity'];
    if ($questions->count() < $cantidadSolicitada) {
        $cantidadSolicitada = $questions->count();
    }

    // Generar lista de preguntas en el mismo formato que iniciarExamen()
    $listaPreguntas = $questions
        ->shuffle()
        ->take($cantidadSolicitada)
        ->map(function ($question) {
            $respuestas = $question->alternatives->map(function ($alternative) {
                return [
                    'description' => $alternative->description, // Asegurar que use "description"
                    'id' => $alternative->id,
                    'is_correct' => $alternative->is_correct,
                ];
            });

            return [
                'id' => $question->id,
                'description' => $question->description, // Asegurar que use "description"
                'respuestas' => $respuestas->toArray(),
            ];
        })->toArray();

    return view('user.examen', compact('test', 'listaPreguntas'));
}
}
