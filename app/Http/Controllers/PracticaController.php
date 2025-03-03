<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSession;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PracticaController extends Controller
{
    /**
     * Muestra la lista de prácticas para el administrador.
     */
    public function indexPractica()
    {
        if (auth()->user()->role === "ADMIN") {
            $tests = Test::where('state', true)
                ->where('is_practice', true)
                ->where('test_type_id', '!=', 5)
                ->get();

            return view('admin.practices.index', compact('tests'));
        }
        return redirect()->to('/login');
    }

    public function generarPractica(Request $request)
    {
        try {
            Log::info('📩 Recibiendo datos en generarPractica', $request->all());

            $validatedData = $request->validate([
                'test_id' => 'required|exists:tests,id',
                'quantity' => 'required|integer|min:1',
            ]);

            // Buscar el test original
            $test = Test::findOrFail($validatedData['test_id']);
            Log::info('✅ Test encontrado:', ['test_id' => $test->id]);

            // Verificar si el test tiene preguntas
            if (!$test->questions()->exists()) {
                return response()->json(['error' => 'El test seleccionado no tiene preguntas.'], 400);
            }

            // Seleccionar preguntas aleatorias
            $cantidadSolicitada = min($validatedData['quantity'], $test->questions()->count());
            $preguntasSeleccionadas = $test->questions()->inRandomOrder()->limit($cantidadSolicitada)->get();

            Log::info('📌 Preguntas seleccionadas:', $preguntasSeleccionadas->pluck('id')->toArray());

            // Crear una nueva práctica
            $nuevoTest = Test::create([
                'test_type_id' => 5, // Test generado
                'name' => "Práctica - " . now()->format('d-m-Y H:i'),
                'quantity' => $preguntasSeleccionadas->count(),
                'is_practice' => false, // No es una práctica predefinida
                'state' => true,
            ]);

            // Asociar preguntas a la nueva práctica mediante una relación muchos a muchos
            $nuevoTest->questions()->attach($preguntasSeleccionadas->pluck('id'));

            // Crear una nueva sesión de práctica
            $testSession = TestSession::create([
                'user_id' => auth()->id(),
                'test_id' => $nuevoTest->id,
                'session_id' => uniqid(),
            ]);

            // Guardar en la sesión
            session([
                'test_id' => $nuevoTest->id,
                'test_session_id' => $testSession->session_id,
                'preguntas_examen' => $preguntasSeleccionadas->pluck('id')->toArray(), // Guardar solo los IDs
            ]);

            Log::info('✅ Práctica generada correctamente', [
                'test_id' => session('test_id'),
                'test_session_id' => session('test_session_id'),
            ]);

            // Preparar lista de preguntas para la vista
            $listaPreguntas = $preguntasSeleccionadas->map(function ($question) {
                return [
                    'id' => $question->id,
                    'descripcion' => $question->description,
                    'respuestas' => $question->alternatives->map(fn($alt) => [
                        'id' => $alt->id,
                        'texto' => $alt->description,
                        'es_correcta' => $alt->is_correct,
                    ])->toArray(),
                ];
            })->toArray();

            // ✅ Respuesta JSON con la URL de redirección y preguntas
            return response()->json([
                'success' => true,
                'redirect' => route('user.examen', ['test_id' => session('test_id')]),
                'preguntas' => $listaPreguntas, // Enviar preguntas para debug
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Error en generarPractica:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Error interno en el servidor. Revisa los logs.'], 500);
        }
    }


    /**
     * Muestra el formulario para crear una nueva práctica.
     */
    public function createPractica()
    {
        if (auth()->user()->role === "ADMIN") {
            return view('admin.practices.create');
        }
        return redirect()->to('/login');
    }

    /**
     * Lista los exámenes de práctica disponibles.
     */
    public function practicaTema()
    {
        $tests = Test::where('state', true)
            ->where('is_practice', true) // Solo prácticas predefinidas
            ->where('test_type_id', '!=', 5) // Excluir las generadas aleatoriamente
            ->get();

        Log::info("🧐 Tests disponibles para prácticas por tema:", $tests->toArray());

        return view('user.seleccionar-practica', compact('tests'));
    }
}
