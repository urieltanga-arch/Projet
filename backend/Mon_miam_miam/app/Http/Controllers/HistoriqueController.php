<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reclamation;

class HistoriqueController extends Controller
{
    /**
     * Afficher l'historique des commandes de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Récupérer les filtres
        $dateFilter = $request->get('date', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // Query de base - toutes les commandes de l'utilisateur
        $query = Commande::where('user_id', $user->id)
            ->with(['items.plat'])
            ->orderBy('created_at', 'desc');
        
        // Filtre par date
        switch ($dateFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            // 'all' = pas de filtre
        }
        
        // Filtre par statut
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        $commandes = $query->paginate(10);
        
        return view('historique.index', compact('commandes', 'dateFilter', 'statusFilter'));
    }
    
    /**
     * Voir les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with(['items.plat'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        
        return view('historique.show', compact('commande'));
    }
    
    /**
     * Signaler un problème sur une commande
     */
    public function signalerProbleme(Request $request, $id)
{
    // Recherche la commande qui appartient à l'utilisateur authentifié
    $commande = Commande::where('user_id', auth()->id())->findOrFail($id);
    
    // Valide les données entrantes
    $validated = $request->validate([
        'type_probleme' => 'required|string|in:Problème de qualité,Quantité incorrecte,Retard de livraison,Article manquant,Autre', // Soyez plus précis pour la sécurité
        'description' => 'required|string|max:500'
    ]);
    
    // Crée la réclamation en base de données
    Reclamation::create([
        'commande_id' => $commande->id,
        'type_probleme' => $validated['type_probleme'],
        'description' => $validated['description'],
        'statut' => 'non_traitee' // Défini par défaut, mais c'est bien d'être explicite
    ]);
    
    // Retourne une réponse de succès
    return response()->json([
        'success' => true,
        'message' => 'Votre réclamation a été enregistrée. Nous vous contacterons sous peu.'
    ]);
}
    
    /**
     * Confirmer la livraison d'une commande
     */
    public function confirmerLivraison($id)
    {
        $commande = Commande::where('user_id', auth()->id())->findOrFail($id);
        
        if ($commande->status === 'en_livraison') {
            $commande->update([
                'status' => 'livree',
                'livree_a' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Merci d\'avoir confirmé la livraison !'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Cette commande ne peut pas être confirmée.'
        ], 400);
    }
}