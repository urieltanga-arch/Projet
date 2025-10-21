<?php

namespace App\Http\Controllers;
use App\Models\Plat;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Récupérer tous les plats disponibles, triés par catégorie
        $plats = Plat::where('is_available', true)
                     ->orderBy('category')
                     ->orderBy('name')
                     ->get();
        
        return view('menu', compact('plats'));
    }

    public function show($id)
    {
        $plat = Plat::findOrFail($id);
        return view('menu.show', compact('plat'));
    }
}
