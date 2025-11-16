<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array|string  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Si el perfil del usuario no está dentro de los roles permitidos
        if (!in_array($user->perfil, $roles)) {
            return response()->json(['message' => 'No tienes permisos para esta acción'], 403);
        }

        return $next($request);
    }
}
