<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Question;
use App\Models\TestType;
use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestRequest;
use Illuminate\Support\Facades\Log;

class AdminTestController extends Controller
{
    /**
     * Mostrar la lista de exámenes.
     */
    public function index()
    {
        $tests = Test::with('test_type')->where('state', true)->where('is_practice', false)->get();
        return view('admin.tests.index', compact('tests'));
    }


    /**
     * Mostrar la lista de prácticas.
     */
    public function indexPractica()
    {
        $tests = Test::where('state', true)->where('is_practice', true)->get();
        return view('admin.practices.index', compact('tests'));
    }

    /**
     * Mostrar el formulario para crear un nuevo examen.
     */
    public function create()
    {
        $test_types = TestType::all();
        return view('admin.tests.create', compact('test_types'));
    }

    /**
     * Mostrar el formulario para crear una nueva práctica.
     */
    public function createPractica()
    {
        $test_types = TestType::all();
        return view('admin.practices.create', compact('test_types'));
    }

    /**
     * Guardar un nuevo examen o práctica en la base de datos.
     */
    public function store(StoreTestRequest $request)
    {
        try {
            $test = Test::create($request->validated());

            $redirectRoute = $test->is_practice ? 'admin.practices.index' : 'admin.tests.index';
            return redirect()->route($redirectRoute)->with('success', 'El examen/práctica se ha creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear el examen/práctica: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear el examen/práctica. Por favor, inténtelo de nuevo.');
        }
    }


    /**
     * Mostrar el formulario para editar un examen existente.
     */
    public function edit($id)
    {
        $test = Test::findOrFail($id);
        $test_types = TestType::all();
        $questions = Question::all();
        return view('admin.tests.edit', compact('test', 'test_types', 'questions'));
    }

    /**
     * Actualizar un examen existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'test_type_id' => 'required',
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'state' => 'nullable|boolean',
        ]);

        $test = Test::findOrFail($id);
        $test->test_type_id = $validatedData['test_type_id'];
        $test->name = $validatedData['name'];
        $test->quantity = $validatedData['quantity'];
        $test->state = $request->has('state');
        $test->save();

        $redirectRoute = $test->is_practice ? 'admin.practices.index' : 'admin.tests.index';
        return redirect()->route($redirectRoute)->with('success', 'El examen/práctica se ha actualizado correctamente.');
    }

    /**
     * Desactivar un examen existente (cambio de estado).
     */
    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        $test->state = false;
        $test->save();

        return redirect()->route('admin.tests.index')->with('success', 'El examen ha sido desactivado.');
    }

    /**
     * Buscar un examen y sus preguntas.
     */
    public function buscarExamen($id)
    {
        $test = Test::with(['questions' => function ($query) {
            $query->with('alternatives')->orderBy('created_at');
        }])->findOrFail($id);

        return response()->json(['data' => $test]);
    }
}
