<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            // Redirection selon le rôle
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            
            if ($user->isManager()) {
                return redirect('/manager/dashboard');
            }
            
            if ($user->isEmployee()) {
                return redirect('/employee/dashboard');
            }
            
            // Par défaut : étudiant
            return redirect('/dashboard');
        }
    }

    return $next($request);
}
}
