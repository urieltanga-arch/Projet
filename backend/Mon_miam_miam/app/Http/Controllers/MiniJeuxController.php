<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MiniJeuxController extends Controller
{
    /**
     * Afficher la page des mini-jeux
     */
    public function index()
    {
        // Récupérer les événements actifs à venir
        $events = Event::where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(function($event) {
                // Décoder les participants JSON
                $participants = json_decode($event->participants ?? '[]', true);
                $event->participants = collect($participants);
                return $event;
            });
        
        return view('minijeux.index', compact('events'));
    }
    
    /**
     * Tourner la roue de fortune
     */
    public function spinRoue(Request $request)
    {
        $user = Auth::user();
        $cost = 10;
        
        // Vérifier si l'utilisateur a assez de points
        if ($user->total_points < $cost) {
            return response()->json([
                'success' => false,
                'message' => 'Points insuffisants'
            ], 400);
        }
        
        // Déduire le coût
        $user->total_points -= $cost;
        $user->save();
        
        // Segments de la roue (12 segments)
        $segments = [0, 15, 30, 45, 60, 75, 0, 15, 30, 45, 60, 75];
        
        // Choisir un segment aléatoire
        $winningSegment = rand(0, count($segments) - 1);
        $points = $segments[$winningSegment];
        
        // Ajouter les points gagnés
        if ($points > 0) {
            $user->total_points += $points;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'segment' => $winningSegment,
            'points' => $points,
            'total_points' => $user->total_points
        ]);
    }
    
    /**
     * Terminer le quiz
     */
    public function finishQuiz(Request $request)
    {
        $user = Auth::user();
        $score = $request->input('score', 0);
        
        // Calculer les points gagnés (20 points par bonne réponse, max 100)
        $pointsGagnes = min($score, 100);
        
        if ($pointsGagnes > 0) {
            $user->total_points += $pointsGagnes;
            $user->save();
        }
        
        return response()->json([
            'success' => true,
            'points' => $pointsGagnes,
            'total_points' => $user->total_points
        ]);
    }
    
    /**
     * Participer à un événement
     */
    public function participateEvent(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        // Décoder les participants actuels
        $participants = json_decode($event->participants ?? '[]', true);
        
        // Vérifier si l'utilisateur est déjà inscrit
        if (in_array($user->id, $participants)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous êtes déjà inscrit à cet événement'
            ], 400);
        }
        
        // Vérifier s'il reste de la place
        if ($event->current_participants >= $event->max_participants) {
            return response()->json([
                'success' => false,
                'message' => 'Cet événement est complet'
            ], 400);
        }
        
        // Ajouter l'utilisateur
        $participants[] = $user->id;
        $event->participants = json_encode($participants);
        $event->current_participants = count($participants);
        $event->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie!'
        ]);
    }
    
    /**
     * Obtenir les points de l'utilisateur
     */
    public function getUserPoints()
    {
        return response()->json([
            'points' => Auth::user()->total_points ?? 0
        ]);
    }
}