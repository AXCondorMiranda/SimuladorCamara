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
use App\Http\Controllers\UserTestController;

// Rutas de autenticación
Route::get('/login', [SessionsController::class, 'create'])->name('login.index');
Route::post('/login', [SessionsController::class, 'store'])->name('login.store');
Route::get('/logout', [SessionsController::class, 'logout'])->name('logout');

// Grupo de rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('inicio');
    });

    Route::get('/seleccionar-tipo', function () {
        return view('user.seleccionar-tipo');
    })->name('seleccionar.tipo');

    Route::get('/usuario-tipo/{id}', [SessionsController::class, 'typeUser'])->name('usuario.tipo');

    Route::middleware(['checkAffiliateType'])->group(function () {
        Route::get('/inicio', function () {
            return view('user.home');
        })->name('inicio');

        // 📌 EXÁMENES Y PRÁCTICAS - USAN LA MISMA PLANTILLA
        Route::post('/generar-practica', [PracticaController::class, 'generarPractica'])->name('generar.practica');
        Route::get('/iniciar-examen', [UserTestController::class, 'iniciarExamen'])->name('user.iniciar.examen');

        // 📌 Prácticas por tema
        Route::get('/practica-tema', [PracticaController::class, 'practicaTema'])->name('practica.tema');

        // 📌 Guardar respuestas después de la práctica o el examen
        Route::post('/guardar-respuestas', [ExamenController::class, 'guardarRespuestas'])->name('guardar.respuestas');

        // 📌 Mostrar resultado del examen
        Route::get('/examen/resultado/{test_session_id}', [ResultadoController::class, 'mostrarResultados'])->name('examen.resultado');

        // 📌 Otras vistas de usuario
        Route::get('/puntajes', [ResultadoController::class, 'answersHistory'])->name('puntajes');
        Route::view('/temario', 'user.temario')->name('temario');
        Route::view('/simulacros', 'user.simulacros')->name('simulacros');
        Route::get('/examen/{test_id}', [UserTestController::class, 'mostrarExamen'])->name('user.examen');

    });

    // 📌 Rutas de administrador
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/examenes', [AdminTestController::class, 'index'])->name('examen.index');
        Route::get('/practicas', [AdminTestController::class, 'indexPractica'])->name('practica.index');
        Route::get('/practicas/create', [AdminTestController::class, 'createPractica'])->name('practica.create');

        Route::get('/preguntas', [QuestionController::class, 'index'])->name('preguntas.index');
        Route::get('/examenes/create', [AdminTestController::class, 'create'])->name('examen.create');
        Route::get('/examenes/buscar/{id}', [AdminTestController::class, 'buscarExamen'])->name('buscar.examen');
        Route::get('/examenes/{id}/edit', [AdminTestController::class, 'edit'])->name('examen.edit');
        Route::post('/examenes', [AdminTestController::class, 'store'])->name('examen.store');
        Route::put('/examenes/{id}', [AdminTestController::class, 'update'])->name('examen.update');
        Route::delete('/examenes/{id}', [AdminTestController::class, 'destroy'])->name('examen.destroy');
    });

    // 📌 Otras rutas de administrador
    Route::get('/register', [RegisterController::class, 'create'])->name('admin.register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::resource('/usuario', UserController::class);
    Route::resource('/preguntas', QuestionController::class);
    Route::post('/respuesta', [ResultadoController::class, 'store'])->name('respuesta.examen');
});
