<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckAffiliateType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Verifica si el usuario está autenticado y si tiene asignado un tipo de afiliado
        if ($user && !$user->affiliate_type_id) {
            // Si no tiene un tipo de afiliado, redirige a la vista de selección de tipo
            return redirect()->route('seleccionar.tipo');
        }

        return $next($request);
    }
}
