<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        // Pour l'instant, créons des jeux en dur (sans base de données)
        $games = collect([
            (object)[
                'id' => 1,
                'title' => 'Memory-Card-Game',
                'thumbnail' => null,
                'points' => 15,
                'folder_name' => 'Games/jeu1/index.html' // Chemin vers votre jeu
            ]
            
        ]);

        return view('games.index', compact('games'));
    }

    public function show($id)
    {
        // Récupérer le jeu (ici en dur, vous pourrez utiliser la DB plus tard)
        $games = collect([
            (object)[
                'id' => 1,
                'title' => 'Memory-Card-Game',
                'game_path' => 'Games/jeu1/index.html'
            ],
            
        ]);

        $game = $games->firstWhere('id', $id);

        if (!$game) {
            abort(404);
        }

        return view('games.show', compact('game'));
    }
}