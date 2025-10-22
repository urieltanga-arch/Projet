<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopClientsController extends Controller
{
    /**
     * Afficher le top 10 des clients
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'semaine'); // semaine ou mois
        
        // Définir la date de début selon la période
        if ($periode === 'semaine') {
            $dateDebut = Carbon::now()->startOfWeek();
        } else {
            $dateDebut = Carbon::now()->startOfMonth();
        }
        
        // Récupérer le top 10 des clients étudiants
        $topClients = User::where('role', 'student') // Ou 'etudiant' selon ton système
            ->withCount(['commandes' => function($query) use ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut)
                      ->where('status', '!=', 'annule'); // Exclure les commandes annulées
            }])
            ->withSum(['commandes as total_depense' => function($query) use ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut)
                      ->where('status', '!=', 'annule');
            }], 'montant_total')
            ->having('commandes_count', '>', 0) // Uniquement ceux qui ont commandé
            ->orderByDesc('total_depense')
            ->orderByDesc('commandes_count')
            ->take(10)
            ->get();
        
        return view('top-clients', compact('topClients', 'periode'));
    }
}