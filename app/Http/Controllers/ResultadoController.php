<?php

namespace App\Http\Controllers;

use App\Models\RespuestaUsuario; // Cambiado a la tabla correcta
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResultadoController extends Controller
{
    public function mostrarResultados()
    {
        $sessionTestId = session('test_session_id');

        if (!$sessionTestId) {
            return redirect()->route('inicio')->with('error', 'No se encontraron resultados para este examen.');
        }

        $resultados = RespuestaUsuario::where('test_session_id', $sessionTestId) // Se cambia al modelo correcto
            ->where('user_id', auth()->id())
            ->with('question.correctAnswer')
            ->get();

        return view('user.examen-resultado', compact('resultados'));
    }
}
