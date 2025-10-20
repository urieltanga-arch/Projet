<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Afficher la page de gestion des commandes
     */
    public function index(Request $request)
    {
        $query = Commande::with(['items', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtre par recherche (numéro ou nom client)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtre par montant minimum
        if ($request->has('montant') && $request->montant) {
            $query->where('montant_total', '>=', $request->montant);
        }

        $commandes = $query->get()->map(function($commande) {
            // Formater les données pour la vue
            return [
                'id' => $commande->id,
                'numero' => $commande->numero,
                'client_nom' => $commande->user->name ?? 'Client',
                'client_tel' => $commande->user->telephone ?? 'N/A',
                'adresse' => $commande->adresse_livraison,
                'statut' => $commande->statut,
                'montant' => $commande->montant_total,
                'date' => $commande->created_at->format('d/m/Y'),
                'heure' => $commande->created_at->format('H:i'),
                'items_resume' => $this->getItemsResume($commande->items),
                'items' => $commande->items->map(function($item) {
                    return [
                        'nom' => $item->nom,
                        'quantite' => $item->quantite,
                        'prix' => $item->prix_unitaire * $item->quantite
                    ];
                })->toArray()
            ];
        });

        return view('employee.commandes', [
            'commandes' => $commandes,
            'commandesEnAttente' => $commandes->where('statut', 'nouvelle')->count(),
            'reclamationsNonTraitees' => 0 // À implémenter selon votre logique
        ]);
    }

    /**
     * Changer le statut d'une commande
     */
    public function changerStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:nouvelle,en_preparation,prete,en_livraison,livree,annulee'
        ]);

        $commande = Commande::findOrFail($id);
        
        // Vérifier la progression logique des statuts
        if (!$this->peutChangerStatut($commande->statut, $request->statut)) {
            return response()->json([
                'success' => false,
                'message' => 'Changement de statut invalide'
            ], 400);
        }

        $commande->statut = $request->statut;
        
        // Enregistrer l'horodatage selon le statut
        switch($request->statut) {
            case 'en_preparation':
                $commande->preparation_debut = now();
                break;
            case 'prete':
                $commande->prete_a = now();
                break;
            case 'en_livraison':
                $commande->livraison_debut = now();
                break;
            case 'livree':
                $commande->livree_a = now();
                break;
        }
        
        $commande->save();

        // Logger l'action
        activity()
            ->performedOn($commande)
            ->causedBy(auth()->user())
            ->withProperties(['ancien_statut' => $commande->statut, 'nouveau_statut' => $request->statut])
            ->log('Changement de statut de commande');

        // Envoyer une notification au client (optionnel)
        // event(new CommandeStatutChange($commande));

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);
    }

    /**
     * Annuler une commande
     */
    public function annuler(Request $request, $id)
    {
        $request->validate([
            'raison' => 'required|string|max:500'
        ]);

        $commande = Commande::findOrFail($id);

        // On ne peut annuler que les commandes non livrées
        if (in_array($commande->statut, ['livree', 'annulee'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut pas être annulée'
            ], 400);
        }

        $commande->statut = 'annulee';
        $commande->raison_annulation = $request->raison;
        $commande->annulee_a = now();
        $commande->annulee_par = auth()->id();
        $commande->save();

        // Logger l'action
        activity()
            ->performedOn($commande)
            ->causedBy(auth()->user())
            ->withProperties(['raison' => $request->raison])
            ->log('Annulation de commande');

        // Rembourser le client si nécessaire
        // if ($commande->paye) {
        //     $this->processerRemboursement($commande);
        // }

        // Notifier le client
        // event(new CommandeAnnulee($commande));

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès'
        ]);
    }

    /**
     * Vérifier si le changement de statut est valide
     */
    private function peutChangerStatut($statutActuel, $nouveauStatut)
    {
        // Matrice de transitions autorisées
        $transitions = [
            'nouvelle' => ['en_preparation', 'annulee'],
            'en_preparation' => ['prete', 'annulee'],
            'prete' => ['en_livraison', 'annulee'],
            'en_livraison' => ['livree', 'annulee'],
            'livree' => [],
            'annulee' => []
        ];

        return in_array($nouveauStatut, $transitions[$statutActuel] ?? []);
    }

    /**
     * Générer un résumé des items de la commande
     */
    private function getItemsResume($items)
    {
        if ($items->count() === 0) {
            return 'Aucun item';
        }

        $resume = [];
        foreach ($items->take(3) as $item) {
            $resume[] = $item->nom;
        }

        $text = implode(' + ', $resume);
        
        if ($items->count() > 3) {
            $text .= '...';
        }

        return $text;
    }

    /**
     * Obtenir les statistiques des commandes
     */
    public function statistiques(Request $request)
    {
        $periode = $request->get('periode', 'jour');

        switch ($periode) {
            case 'semaine':
                $dateDebut = now()->startOfWeek();
                break;
            case 'mois':
                $dateDebut = now()->startOfMonth();
                break;
            case 'annee':
                $dateDebut = now()->startOfYear();
                break;
            default:
                $dateDebut = now()->startOfDay();
        }

        $stats = [
            'total_commandes' => Commande::where('created_at', '>=', $dateDebut)->count(),
            'en_cours' => Commande::whereIn('statut', ['nouvelle', 'en_preparation', 'prete', 'en_livraison'])
                ->where('created_at', '>=', $dateDebut)
                ->count(),
            'livrees' => Commande::where('statut', 'livree')
                ->where('created_at', '>=', $dateDebut)
                ->count(),
            'annulees' => Commande::where('statut', 'annulee')
                ->where('created_at', '>=', $dateDebut)
                ->count(),
            'chiffre_affaires' => Commande::where('statut', 'livree')
                ->where('created_at', '>=', $dateDebut)
                ->sum('montant_total'),
            'panier_moyen' => Commande::where('created_at', '>=', $dateDebut)
                ->avg('montant_total'),
            'temps_preparation_moyen' => $this->getTempsPreparationMoyen($dateDebut)
        ];

        return response()->json($stats);
    }

    /**
     * Calculer le temps de préparation moyen
     */
    private function getTempsPreparationMoyen($dateDebut)
    {
        $commandes = Commande::whereNotNull('preparation_debut')
            ->whereNotNull('prete_a')
            ->where('created_at', '>=', $dateDebut)
            ->get();

        if ($commandes->isEmpty()) {
            return 0;
        }

        $totalMinutes = 0;
        foreach ($commandes as $commande) {
            $debut = \Carbon\Carbon::parse($commande->preparation_debut);
            $fin = \Carbon\Carbon::parse($commande->prete_a);
            $totalMinutes += $debut->diffInMinutes($fin);
        }

        return round($totalMinutes / $commandes->count());
    }

    /**
     * Imprimer un ticket de commande
     */
    public function imprimerTicket($id)
    {
        $commande = Commande::with(['items', 'user'])->findOrFail($id);

        // Générer un PDF ou renvoyer une vue imprimable
        return view('employee.ticket', compact('commande'));
    }
}