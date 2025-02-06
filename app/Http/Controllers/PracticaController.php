<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestSession;
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
     * Genera una práctica con preguntas aleatorias.
     */
    public function generarPractica(Request $request)
    {
        $validatedData = $request->validate([
            'test_id' => 'required|exists:tests,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $test = Test::findOrFail($validatedData['test_id']);

        // Crear una nueva sesión de prueba
        $sessionTest = new TestSession();
        $sessionTest->user_id = auth()->id();
        $sessionTest->test_id = $test->id;
        $sessionTest->session_id = uniqid(); // ID único para la práctica actual
        $sessionTest->save();

        session(['test_session_id' => $sessionTest->session_id]);
        Log::info('✅ Test Session ID guardado en sesión:', ['test_session_id' => session('test_session_id')]);

        $listaPreguntas = $test->questions->shuffle()->take($validatedData['quantity'])->map(function ($question) {
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

        return view('user.examen', compact('test', 'listaPreguntas', 'sessionTest'));
    }

    /**
     * Lista los exámenes de práctica disponibles.
     */
    public function practicaTema()
    {
        $tests = Test::where('state', true)
            ->where('is_practice', true)
            ->where('quantity', '>', 0)
            ->where('test_type_id', '!=', 5)
            ->has('questions')
            ->get();

        return view('user.seleccionar-practica', compact('tests'));
    }
}
