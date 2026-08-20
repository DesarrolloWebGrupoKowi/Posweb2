<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Guardar la URL completa (incluyendo query string)
            session()->put('url.intended', $request->fullUrl());

            // Redirigir a la ruta de login
            return route('login');
        }

        return null;
    }
}
