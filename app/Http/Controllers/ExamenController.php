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
     * Guarda las respuestas del usuario en la base de datos.
     */
    public function guardarRespuestas(Request $request)
    {
        try {
            Log::info('📩 Recibiendo respuestas:', $request->all());

            // Validar que se recibieron las respuestas y los IDs requeridos
            $validatedData = $request->validate([
                'respuestasExamen' => 'required|array|min:1',
                'test_id' => 'required|integer|exists:tests,id',
                'test_session_id' => 'required|exists:test_sessions,session_id',
            ]);

            $testId = $validatedData['test_id'];
            $testSessionId = $validatedData['test_session_id'];
            $respuestas = $validatedData['respuestasExamen'];

            // Validar cada respuesta antes de insertarla
            foreach ($respuestas as $preguntaId => $respuestaData) {
                if (!isset($respuestaData['respuesta']) || !isset($respuestaData['correcta'])) {
                    Log::warning('⚠️ Respuesta incompleta detectada.', ['pregunta_id' => $preguntaId, 'data' => $respuestaData]);
                    continue; // Saltar esta respuesta
                }

                // Guardar la respuesta en la base de datos
                RespuestaUsuario::create([
                    'test_session_id' => $testSessionId,
                    'test_id' => $testId,
                    'question_id' => $preguntaId,
                    'respuesta' => (string) $respuestaData['respuesta'], // Asegurar que es string
                    'es_correcta' => (int) $respuestaData['correcta'], // Convertir a entero
                    'user_id' => auth()->id(),
                ]);
            }

            Log::info('✅ Respuestas guardadas correctamente.', [
                'test_id' => $testId,
                'test_session_id' => $testSessionId,
                'total_respuestas' => count($respuestas)
            ]);

            return response()->json(['success' => 'Respuestas guardadas correctamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Error de validación', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Datos inválidos.', 'detalles' => $e->errors()], 400);
        } catch (\Exception $e) {
            Log::error('❌ Error al guardar respuestas', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }





    /**
     * Muestra el resumen del examen con respuestas correctas e incorrectas.
     */
    public function resultado()
    {
        try {
            $userId = auth()->id();
            $testId = session('test_id');

            if (!$testId) {
                return redirect()->route('inicio')->with('error', 'No hay examen en proceso.');
            }

            // Obtener respuestas del usuario para el examen en la tabla respuestas_usuarios
            $resultados = RespuestaUsuario::with('question')
                ->where('user_id', $userId)
                ->where('test_id', $testId)
                ->get();

                Log::info('📌 Respuestas encontradas:', $resultados->toArray());
            // Verificar si la relación se ha cargado correctamente
            foreach ($resultados as $resultado) {
                if ($resultado->question === null) {
                    Log::warning("⚠️ Pregunta con ID {$resultado->question_id} no encontrada.");
                    dd("Error: La pregunta con ID {$resultado->question_id} no existe o no se está cargando correctamente.");
                }
            }

            if ($resultados->isEmpty()) {
                Log::warning("⚠️ No hay respuestas registradas para el usuario.");
                return redirect()->route('inicio')->with('error', 'No hay respuestas registradas.');
            }

            return view('user.examen-resultado', [
                'listCorrects' => $resultados->where('es_correcta', 1),
                'listIncorrects' => $resultados->where('es_correcta', 0),
                'result' => [
                    'total_correct' => $resultados->where('es_correcta', 1)->count(),
                    'total_incorrect' => $resultados->where('es_correcta', 0)->count(),
                ],
                'resultados' => $resultados
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error al mostrar resultados', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('inicio')->with('error', 'Error al mostrar los resultados.');
        }
    }
}
