<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Test;
use App\Models\Question;
use App\Models\Alternative;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index()
    {
        if(auth()->user()->role==="ADMIN"){
            $tests = Test::where('state', true)->get();
            return view('admin.questions.create',compact('tests'));
        }
        return redirect()->to('/login');

    }

    public function create()
    {
        if(auth()->user()->role==="ADMIN"){
            $tests = Test::where('state', true)->where('test_type_id', '!=', '5')->get();
            return view('admin.questions.create',compact('tests'));
        }
        return redirect()->to('/login');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $idExam = $request->idExam;
            $test = Test::find($idExam);
            $quantity = $test->quantity;

            if ($test) {
                for ($i = 1; $i <= $quantity; $i++) {
                    $paramName = 'P' . $i;
                    $paramDbValue = 'P' . $i . 'DB';
                    $paramValue = $request->input($paramName);
                    $paramId = $request->input($paramDbValue);
                    $question = new Question();

                    Log::debug("Pregunta: " . $paramValue);

                    if($paramId == "0") {
                        $question->description = $paramValue;
                        $test->questions()->save($question);
                    } else {
                        $question = Question::findOrFail($paramId);
                        $question->description = $paramValue;
                        $question->save();
                    }

                    for ($j = 1; $j <= 5; $j++) {
                        $paramName1 = $paramName . 'A' . $j;
                        $paramDbValue1 = $paramName1 . 'DB';
                        $paramValue1 = $request->input($paramName1);
                        $paramId1 = $request->input($paramDbValue1);
                        $alternative = new Alternative();

                        if($paramId1 == "0") {
                            $alternative->description = $paramValue1;
                            $alternative->is_correct = ($request->input('radioname' . $paramName . $j) == 'on') ? true : false;
                            $question->alternatives()->save($alternative);
                        } else {
                            $alternative = Alternative::findOrFail($paramId1);
                            $alternative->description = $paramValue1;
                            $alternative->is_correct = ($request->input('radioname' . $paramName . $j) == 'on') ? true : false;
                            $alternative->save();
                        }
                    }
                }

                DB::commit();

                return redirect()->route('examen.index')->with('success', 'Preguntas agregadas exitosamente');
            } else {
                return redirect()->route('examen.index')->with('error', 'El examen no existe');
            }
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('examen.index')->with('error', 'Ocurrió un error al guardar las preguntas');
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

}
