<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    /**
     * Verifica que el revisor autenticado tenga uno de los roles permitidos.
     *
     * Uso en rutas: middleware('rol:Administrador')
     *               middleware('rol:Administrador,Revisor')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles  Roles permitidos para la ruta
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $revisor = Auth::guard('revisor')->user();

        if (!$revisor || !in_array($revisor->rol, $roles)) {
            return redirect()->route('revisores.lista-espera')
                ->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        return $next($request);
    }
}

