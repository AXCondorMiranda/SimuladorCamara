<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\PracticaController;
use App\Http\Controllers\ResultadoController;

// Rutas de autenticación
Route::get('/login', [SessionsController::class, 'create'])->name('login.index');
Route::post('/login', [SessionsController::class, 'store'])->name('login.store');
Route::get('/logout', [SessionsController::class, 'logout'])->name('logout');

// Grupo de rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login.index');
    });

    Route::get('/seleccionar-tipo', function () {
        return view('user.seleccionar-tipo');
    })->name('seleccionar.tipo');

    Route::get('/usuario-tipo/{id}', [SessionsController::class, 'typeUser'])->name('usuario.tipo');

    Route::middleware(['checkAffiliateType'])->group(function () {
        Route::get('/inicio', function () {
            return view('user.home');
        })->name('inicio');

        // Exámenes
        Route::get('/examen/iniciar', [ExamenController::class, 'iniciarExamen'])->name('examen.iniciar');
        Route::get('/examen/resultado/{test_id}', [ResultadoController::class, 'resultado'])->name('examen.resultado');
        Route::post('/guardar-respuestas', [ExamenController::class, 'guardarRespuestas'])->name('guardar.respuestas');

        // Prácticas y Simulacros
        Route::post('/practica', [PracticaController::class, 'generarPractica'])->name('generar.practica');
        Route::get('/practica', [PracticaController::class, 'indexPractica'])->name('practica.index');
        Route::get('/practica-tema', [PracticaController::class, 'practicaTema'])->name('practica.tema');
        Route::post('/simulacros', [PracticaController::class, 'generarSimulacro'])->name('generar.simulacro');

        // Otras vistas de usuario
        Route::get('/temario', function () {
            return view('user.temario');
        })->name('temario');
        Route::get('/simulacros', function () {
            return view('user.simulacros');
        })->name('simulacros');
        Route::get('/puntajes', [ResultadoController::class, 'answersHistory'])->name('historial.puntajes');
    });

    // Rutas de administrador
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/examenes', [AdminTestController::class, 'index'])->name('examen.index');
        Route::get('/admin/practicas', [AdminTestController::class, 'indexPractica'])->name('admin.practica.index');
        Route::get('/admin/practicas/create', [AdminTestController::class, 'createPractica'])->name('practica.create');
        Route::get('/preguntas', [QuestionController::class, 'index'])->name('preguntas.index');
        Route::get('/admin/examenes/create', [AdminTestController::class, 'create'])->name('examen.create');
        Route::get('/admin/examenes/buscar/{id}', [AdminTestController::class, 'buscarExamen'])->name('buscar.examen');
        Route::get('/admin/examenes/{id}/edit', [AdminTestController::class, 'edit'])->name('examen.edit');
        Route::post('/admin/examenes', [AdminTestController::class, 'store'])->name('examen.store');
        Route::put('/admin/examenes/{id}', [AdminTestController::class, 'update'])->name('examen.update');
        Route::delete('/admin/examenes/{id}', [AdminTestController::class, 'destroy'])->name('examen.destroy');
    });

    // Otras rutas de administrador
    Route::get('/register', [RegisterController::class, 'create'])->name('admin.register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::resource('/usuario', UserController::class);
    Route::resource('/preguntas', QuestionController::class);
    Route::post('/respuesta', [ResultadoController::class, 'store'])->name('respuesta.examen');
});
