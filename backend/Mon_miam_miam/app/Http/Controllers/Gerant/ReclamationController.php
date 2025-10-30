<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Commande;
use Illuminate\Http\Request;


class ReclamationController extends Controller
{
  
public function index(Request $request)
{
    $periode = $request->get('periode', 'jour');
    
    // Filtrer par période
    $dateDebut = match($periode) {
        'jour' => now()->startOfDay(),
        'semaine' => now()->startOfWeek(),
        'mois' => now()->startOfMonth(),
    };
    
    $reclamations = Reclamation::with(['client', 'employe'])
        ->where('created_at', '>=', $dateDebut)
        ->orderBy('priorite', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
    
    $stats = [
        'total' => $reclamations->count(),
        'en_attente' => $reclamations->where('statut', 'en_attente')->count(),
        'en_traitement' => $reclamations->where('statut', 'en_traitement')->count(),
        'traitees' => $reclamations->where('statut', 'traitee')->count(),
        'resolues' => Reclamation::where('statut', 'resolue')
            ->whereMonth('created_at', now()->month)
            ->count(),
    ];
    
    return view('admin.reclamations.index', compact('reclamations', 'stats', 'periode'));
}
}