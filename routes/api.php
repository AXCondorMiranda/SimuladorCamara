<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionsController;


Route::post('/login', [SessionsController::class, 'loginApi']);
