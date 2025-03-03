<?php

namespace App\Http\Controllers;

use App\Models\RespuestaUsuario; // Cambiado a la tabla correcta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TestSession;
use Illuminate\Support\Facades\Log;

class ResultadoController extends Controller
{
    public function answersHistory()
    {
        try {
            $userId = Auth::id();

            // Recuperar sesiones de exámenes del usuario con sus respuestas
            $historialExamenes = TestSession::where('user_id', $userId)
                ->with('respuestasUsuarios') // Relación con las respuestas del usuario
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($session) {
                    return [
                        'fecha' => $session->created_at->format('d/m/Y H:i'),
                        'test' => 'Práctica - ' . $session->created_at->format('d-m-Y H:i'),
                        'puntaje' => $session->respuestasUsuarios->where('es_correcta', 1)->count() . '/' . $session->respuestasUsuarios->count(),
                        'session_id' => $session->session_id,
                    ];
                });

            return view('user.puntajes', compact('historialExamenes')); // ✅ Retorna la vista correctamente
        } catch (\Exception $e) {
            return redirect()->route('inicio')->with('error', 'Error al obtener el historial de puntajes.');
        }
    }


    public function mostrarResultados($test_session_id)
    {
        if (!$test_session_id) {
            return redirect()->route('inicio')->with('error', 'No se encontraron resultados para este examen.');
        }

        // Obtener la sesión del examen y marcarla como finalizada
        $testSession = TestSession::where('session_id', $test_session_id)->first();

        if ($testSession) {
            $testSession->update([
                'status' => 'finalizado',
                'end_time' => now(),
            ]);
        }

        $resultados = RespuestaUsuario::where('test_session_id', $test_session_id)
            ->where('user_id', auth()->id())
            ->with('question.correctAnswer')
            ->get();

        $totalCorrectas = $resultados->where('es_correcta', 1)->count();
        $totalIncorrectas = $resultados->where('es_correcta', 0)->count();

        if ($resultados->isEmpty()) {
            return redirect()->route('inicio')->with('error', 'No se encontraron resultados para este examen.');
        }

        return view('user.examen-resultado', compact('resultados', 'totalCorrectas', 'totalIncorrectas'));
    }
}
