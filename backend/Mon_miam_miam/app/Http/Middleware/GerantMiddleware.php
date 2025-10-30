<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GerantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'gerant') {
            return redirect()->route('login')->with('error', 'Accès réservé aux gérants.');
        }

        return $next($request);
    }
}