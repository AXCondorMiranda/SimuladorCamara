<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Alternative;
use Illuminate\Support\Facades\Log;
use App\Models\Result;
use App\Models\ResultadoExamen; // Importamos el modelo correcto para guardar respuestas

class ResultController extends Controller
{
    public function store(Request $request)
    {
        $idExam = $request->idExam;
        $test = Test::find($idExam);

        if (!$test) {
            return redirect()->route('inicio')->with('error', 'Examen no encontrado.');
        }

        $contCorrectas = 0;
        $contIncorrectas = 0;
        $contMarcadas = 0;
        $userId = auth()->user()->id;
        $questions = $test->questions;
        $listIncorrects = [];
        $listCorrects = [];

        $respuestasUsuario = json_decode($request->input('respuestasExamen'), true);

        foreach ($questions as $question) {
            $paramValue = $respuestasUsuario[$question->id]['respuesta'] ?? null;
            $isCorrect = 0;

            if ($paramValue) {
                $alternatives = $question->alternatives;
                $correctAlternative = $alternatives->where('is_correct', 1)->first();

                if ($correctAlternative) {
                    if ($correctAlternative->description == $paramValue) {
                        $contCorrectas++;
                        $isCorrect = 1;
                        $listCorrects[] = [
                            'question' => $question->description,
                            'options' => ['correct' => $correctAlternative->description]
                        ];
                    } else {
                        $contIncorrectas++;
                        $listIncorrects[] = [
                            'question' => $question->description,
                            'options' => [
                                'correct' => $correctAlternative->description,
                                'incorrect' => $paramValue ?? "No marcada"
                            ]
                        ];
                    }
                }
                $contMarcadas++;
            }

            // Guardar cada respuesta del usuario en `resultado_examen`
            ResultadoExamen::create([
                'user_id' => $userId,
                'test_id' => $test->id,
                'question_id' => $question->id,
                'respuesta' => $paramValue,
                'es_correcta' => $isCorrect,
            ]);
        }

        // Guardar el resumen del resultado en `results`
        $result = new Result();
        $result->questions_total = $test->questions->count();
        $result->total_marked = $contMarcadas;
        $result->total_correct = $contCorrectas;
        $result->total_incorrect = $contIncorrectas;
        $result->user_id = $userId;
        $result->test_id = $test->id;
        $result->duration = now(); // Registrar el tiempo correctamente
        $result->save();

        return view('user.examen-resultado', compact('result', 'listIncorrects', 'listCorrects'));
    }
}
