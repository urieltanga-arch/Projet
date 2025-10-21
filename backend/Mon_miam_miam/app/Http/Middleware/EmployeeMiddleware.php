<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole(['employee', 'manager', 'admin'])) {
            abort(403, 'Accès refusé - Réservé aux employés');
        }

        return $next($request);
    }
}