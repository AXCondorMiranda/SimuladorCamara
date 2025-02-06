<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Scalar\String_;
use Psy\Util\Str;

class CheckRememberToken
{
    public function handle($request, $next)
    {
        Log::debug("Entra al middleware. Request: " . $request);
        if (Auth::check()) {
            $user = Auth::user();
            $sessionToken = $request->session()->get('remember_token');
            if ($sessionToken !== $user->remember_token) {
                Auth::logoutCurrentDevice();
                return redirect('/login')->withErrors([
                    'session_expired' => 'Tu sesión ha expirado o ha sido iniciada en otro dispositivo.',
                ]);
            }
        }

        return $next($request);
    }
}
