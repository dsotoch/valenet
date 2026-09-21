<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareRol
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $usuario = auth()->user();
        if (!in_array($usuario->rol, $roles, true)) {

            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
