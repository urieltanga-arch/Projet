<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Plat;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si la route contient un ID de plat
        $platId = $request->route('plat') ?? $request->route('id');
        
        if ($platId) {
            $plat = Plat::find($platId);
            
            if ($plat && !$plat->is_available) {
                // Si le plat existe mais n'est pas disponible, retourner une erreur 404
                abort(404, 'Ce plat n\'est plus disponible');
            }
        }
        
        return $next($request);
    }
}
