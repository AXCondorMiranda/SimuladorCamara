<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestSession;
use App\Models\RespuestaUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserTestController extends Controller
{
    /**
     * Genera un examen con preguntas de múltiples temas.
     */
    public function iniciarExamen()
    {
        try {
            Log::info('📝 Iniciando examen para usuario', ['user_id' => auth()->id()]);

            $user = auth()->user();

            // Obtener todas las prácticas activas
            $practicas = Test::where('is_practice', true)
                ->where('state', true)
                ->has('questions')
                ->get();

            Log::info('📌 Prácticas disponibles para el examen', [
                'total_practicas' => $practicas->count(),
                'practicas' => $practicas->pluck('name', 'id')
            ]);

            if ($practicas->isEmpty()) {
                Log::warning('⚠️ No hay prácticas disponibles.');
                return redirect()->route('inicio')->with('error', 'No hay prácticas disponibles.');
            }

            // Calcular cuántas preguntas se tomarán de cada práctica
            $totalPreguntas = 100;
            $preguntasSeleccionadas = collect();
            $preguntasPorPractica = (int) ($totalPreguntas / max($practicas->count(), 1));

            foreach ($practicas as $practica) {
                $preguntas = $practica->questions()
                    ->inRandomOrder()
                    ->take($preguntasPorPractica)
                    ->get();

                $preguntasSeleccionadas = $preguntasSeleccionadas->merge($preguntas);

                Log::info('📌 Preguntas seleccionadas de la práctica ' . $practica->name, [
                    'practica' => $practica->name,
                    'preguntas_seleccionadas' => $preguntas->pluck('description', 'id')
                ]);
            }

            // Si hay menos de 100 preguntas, usar todas las disponibles
            if ($preguntasSeleccionadas->count() < $totalPreguntas) {
                Log::warning('⚠️ Preguntas insuficientes: ' . $preguntasSeleccionadas->count() . ' preguntas disponibles.');
            }

            if ($preguntasSeleccionadas->isEmpty()) {
                Log::error('❌ No se seleccionaron preguntas');
                return redirect()->route('inicio')->with('error', 'No hay preguntas disponibles.');
            }

            // Crear una nueva sesión de examen
            $testSession = TestSession::create([
                'user_id' => $user->id,
                'test_id' => $practicas->first()->id, // Tomar una de las prácticas como referencia
                'session_id' => uniqid(),
            ]);

            // Guardar en sesión
            session()->put('test_id', $testSession->test_id);
            session()->put('test_session_id', $testSession->session_id);
            session()->put('preguntas_examen', $preguntasSeleccionadas->pluck('id')->toArray());
            session()->save();

            session()->save();

            Log::info('✅ Examen generado correctamente', [
                'test_session_id' => $testSession->session_id,
                'total_preguntas' => $preguntasSeleccionadas->count()
            ]);

            return redirect()->route('user.examen', ['test_id' => session('test_id')]);
        } catch (\Exception $e) {
            Log::error('❌ Error en iniciarExamen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('inicio')->with('error', 'Ocurrió un error inesperado.');
        }
    }



    /**
     * Genera una práctica personalizada con preguntas aleatorias de un tema.
     */


    public function seleccionarPractica()
    {
        $tests = Test::where('state', true)->get(); // Filtrar solo los activos
        return view('seleccionar-practica', compact('tests'));
    }


    public function mostrarExamen($test_id)
    {
        try {
            $userId = auth()->id();
            $sessionTestId = session('test_session_id');

            Log::info('📌 Mostrando examen/práctica', [
                'test_id' => $test_id,
                'user_id' => $userId,
                'session' => $sessionTestId
            ]);

            // 🔹 Recuperar preguntas seleccionadas desde la sesión
            $preguntasSeleccionadas = session('preguntas_examen', []);

            if (empty($preguntasSeleccionadas)) {
                Log::error('❌ No se encontraron preguntas en la sesión.');
                return redirect()->route('inicio')->with('error', 'No hay preguntas disponibles para este examen.');
            }

            // 🔹 Asegurar que solo sean IDs numéricos válidos
            $preguntasIds = array_filter($preguntasSeleccionadas, 'is_numeric');

            if (empty($preguntasIds)) {
                Log::error('❌ Los datos en la sesión no contienen IDs válidos.');
                return redirect()->route('inicio')->with('error', 'Error al recuperar las preguntas del examen.');
            }

            // 🔹 Obtener las preguntas de la BD con sus alternativas
            $preguntas = Question::whereIn('id', $preguntasIds)->with('alternatives')->get();

            if ($preguntas->isEmpty()) {
                Log::error('❌ No se encontraron preguntas en la base de datos.', ['preguntas_seleccionadas' => $preguntasSeleccionadas]);
                return redirect()->route('inicio')->with('error', 'No se encontraron preguntas disponibles.');
            }

            Log::info('📌 Preguntas enviadas a la vista', [
                'total_preguntas' => $preguntas->count(),
                'preguntas' => $preguntas->pluck('description', 'id')
            ]);

            return view('user.examen', [
                'preguntas' => $preguntas,
                'test' => Test::find($test_id)
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error en mostrarExamen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('inicio')->with('error', 'Ocurrió un error al cargar el examen.');
        }
    }


    /**
     * Guarda las respuestas del usuario.
     */
    public function guardarRespuestas(Request $request)
    {
        Log::info('📩 Datos recibidos en guardarRespuestas:', $request->all());

        $validatedData = $request->validate([
            'respuestasExamen' => 'required|array',
            'test_id' => 'required|exists:tests,id',
            'test_session_id' => 'required|exists:test_sessions,session_id',
        ]);

        Log::info('✅ Validación pasada');

        foreach ($validatedData['respuestasExamen'] as $preguntaId => $respuesta) {
            if (!is_numeric($preguntaId)) {
                Log::warning("⚠ Se encontró una clave inesperada en respuestasExamen: $preguntaId");
                continue; // Ignorar elementos incorrectos
            }

            RespuestaUsuario::create([
                'test_session_id' => $validatedData['test_session_id'],
                'test_id' => $validatedData['test_id'],
                'question_id' => $preguntaId,
                'respuesta' => $respuesta['respuesta'],
                'es_correcta' => $respuesta['correcta'],
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['success' => 'Respuestas guardadas correctamente.']);
    }
}
