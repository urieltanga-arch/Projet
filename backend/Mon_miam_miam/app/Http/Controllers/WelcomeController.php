<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Récupérer tous les plats disponibles
        $plats = Plat::where('is_available', true)
                    ->orderBy('category')
                    ->orderBy('name')
                    ->get();

        return view('welcome', compact('plats'));
    }
}