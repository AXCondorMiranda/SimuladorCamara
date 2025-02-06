<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SessionsController extends Controller
{
    /**
     * Display the login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'ADMIN') {
                return redirect()->route('examen.index');
            }

            if (!$user->web_access) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Usuario no cuenta con acceso a la plataforma web.',
                ]);
            }

            $request->session()->regenerate();

            if (!$user->affiliate_type_id) {
                return redirect()->route('seleccionar.tipo');
            }

            return redirect()->route('inicio');
        }

        return redirect()->back()->withErrors([
            'email' => 'Credenciales incorrectas.',
            'password' => 'Credenciales incorrectas.',
        ]);
    }

    /**
     * Assign user affiliate type.
     */
    public function typeUser($tipo)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.index');
        }

        $user->affiliate_type_id = $tipo;
        $user->save();

        return redirect()->route('inicio');
    }

    /**
     * Logout the user and invalidate the session.
     */
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login.index');
    }

    /**
     * API Login (optional, if required).
     */
    public function loginApi(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->mobile_access) {
                return response()->json(['message' => 'Usuario no cuenta con accesos'], 401);
            }

            $user->remember_token = Str::random(60);
            $user->save();

            return response()->json(['message' => 'Autenticación exitosa']);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }
}
