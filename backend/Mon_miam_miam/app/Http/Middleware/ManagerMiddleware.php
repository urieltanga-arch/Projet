<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole(['manager', 'admin'])) {
            abort(403, 'Accès refusé - Réservé aux gérants');
        }

        return $next($request);
    }
}