<?php

namespace App\Http\Controllers\Employee;

use App\Models\Commande;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function dashboard(Request $request)
    {
        $periode = $request->get('periode', 'semaine'); // semaine, mois, annee
        
        // Déterminer la date de début selon la période
        $dateDebut = match($periode) {
            'semaine' => now()->startOfWeek(),
            'mois' => now()->startOfMonth(),
            'annee' => now()->startOfYear(),
            default => now()->startOfWeek(),
        };

        // Statistiques principales
        $commandesEnAttente = Commande::where('statut', 'en_attente')->count();
        $commandesAujourdhui = Commande::whereDate('created_at', today())->count();
        $reclamationsNonTraitees = Reclamation::where('statut', 'non_traitee')->count();
        $revenuJour = Commande::whereDate('created_at', today())
            ->whereNotIn('statut', ['annulee'])
            ->sum('montant_total');

        // Activité récente
        $activiteRecente = Commande::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($commande) {
                return [
                    'id' => $commande->id,
                    'numero' => $commande->numero_commande,
                    'type' => $this->getTypeActivite($commande->statut),
                    'temps' => $commande->temps_ecoule,
                    'statut' => $commande->statut,
                    'icone' => $this->getIconeStatut($commande->statut),
                    'couleur' => $this->getCouleurStatut($commande->statut),
                ];
            });

        // Données pour le graphique
        $statistiques = $this->getStatistiquesGraphique($dateDebut, $periode);

        return view('employee.dashboard', compact(
            'commandesEnAttente',
            'commandesAujourdhui',
            'reclamationsNonTraitees',
            'revenuJour',
            'activiteRecente',
            'statistiques',
            'periode'
        ));
    }

    public function commandes(Request $request)
    {
        $statut = $request->get('statut', 'tous');
        
        $query = Commande::with('user')->latest();
        
        if ($statut !== 'tous') {
            $query->where('statut', $statut);
        }
        
        $commandes = $query->paginate(20);
        
        return view('employee.commandes', compact('commandes', 'statut'));
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_preparation,prete,en_livraison,livree,annulee'
        ]);

        $ancienStatut = $commande->statut;
        $commande->statut = $request->statut;

        // Enregistrer les timestamps spécifiques
        if ($request->statut === 'prete') {
            $commande->preparee_a = now();
        } elseif ($request->statut === 'livree') {
            $commande->livree_a = now();
        }

        $commande->save();

        // Notification au client (à implémenter avec Pusher ou Laravel Echo)
        // $commande->user->notify(new CommandeStatutChange($commande, $ancienStatut));

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'commande' => $commande
        ]);
    }

    private function getStatistiquesGraphique($dateDebut, $periode)
    {
        $format = match($periode) {
            'semaine' => '%w', // Jour de la semaine (0-6)
            'mois' => '%d',    // Jour du mois
            'annee' => '%m',   // Mois
            default => '%w',
        };

        $labels = match($periode) {
            'semaine' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'mois' => range(1, now()->daysInMonth),
            'annee' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            default => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        };

        $commandes = Commande::select(
                DB::raw("DATE_FORMAT(created_at, '$format') as periode"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('periode')
            ->pluck('total', 'periode')
            ->toArray();

        $revenus = Commande::select(
                DB::raw("DATE_FORMAT(created_at, '$format') as periode"),
                DB::raw('SUM(montant_total) as total')
            )
            ->where('created_at', '>=', $dateDebut)
            ->whereNotIn('statut', ['annulee'])
            ->groupBy('periode')
            ->pluck('total', 'periode')
            ->toArray();

        return [
            'labels' => $labels,
            'commandes' => $commandes,
            'revenus' => $revenus,
        ];
    }

    private function getTypeActivite($statut)
    {
        return match($statut) {
            'en_attente' => 'Nouvelle Commande',
            'en_preparation' => 'En Préparation',
            'prete' => 'Commande Prête',
            'en_livraison' => 'En Livraison',
            'livree' => 'Commande Livrée',
            'annulee' => 'Commande Annulée',
            default => 'Commande',
        };
    }

    private function getIconeStatut($statut)
    {
        return match($statut) {
            'en_attente' => '⏱️',
            'en_preparation' => '👨‍🍳',
            'prete' => '✅',
            'en_livraison' => '🚚',
            'livree' => '🎉',
            'annulee' => '❌',
            default => '📦',
        };
    }

    private function getCouleurStatut($statut)
    {
        return match($statut) {
            'en_attente' => 'yellow',
            'en_preparation' => 'blue',
            'prete' => 'green',
            'en_livraison' => 'purple',
            'livree' => 'green',
            'annulee' => 'red',
            default => 'gray',
        };
    }

       public function reclamations(Request $request)
    {
        $statut = $request->get('statut', 'tous');
        $search = $request->get('search', '');
        
        $query = Reclamation::with('commande')->latest();
        
        // Filtrer par statut
        if ($statut !== 'tous') {
            $query->where('statut', $statut);
        }
        
        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('type_probleme', 'like', "%{$search}%")
                  ->orWhereHas('commande', function($sq) use ($search) {
                      $sq->where('numero_commande', 'like', "%{$search}%");
                  });
            });
        }
        
        $reclamations = $query->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => Reclamation::count(),
            'non_traitees' => Reclamation::where('statut', 'non_traitee')->count(),
            'en_cours' => Reclamation::where('statut', 'en_cours')->count(),
            'resolues' => Reclamation::where('statut', 'resolue')->count(),
        ];
        
        return view('employee.reclamations', compact('reclamations', 'statut', 'search', 'stats'));
    }
    public function updateStatutReclamation(Request $request, Reclamation $reclamation)
    {
        $request->validate([
            'statut' => 'required|in:non_traitee,en_cours,resolue,fermee'
        ]);

        $reclamation->statut = $request->statut;
        $reclamation->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'reclamation' => $reclamation
        ]);
    }
}