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
        $commandesEnAttente = Commande::where('status', 'en_attente')->count();
        $commandesAujourdhui = Commande::whereDate('created_at', today())->count();
        $reclamationsNonTraitees = Reclamation::where('status', 'non_traitee')->count();
        $revenuJour = Commande::whereDate('created_at', today())
            ->whereNotIn('status', ['annulee'])
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
                    'type' => $this->getTypeActivite($commande->status),
                    'temps' => $commande->temps_ecoule,
                    'status' => $commande->status,
                    'icone' => $this->getIconeStatut($commande->status),
                    'couleur' => $this->getCouleurStatut($commande->stats),
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
        $status = $request->get('status', 'tous');
        
        $query = Commande::with('user')->latest();
        
        if ($status !== 'tous') {
            $query->where('status', $status);
        }
        
        $commandes = $query->paginate(20);
        
        return view('employee.commandes', compact('commandes', 'status'));
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_preparation,prete,en_livraison,livree,annulee'
        ]);

        $ancienStatut = $commande->status;
        $commande->status = $request->status;

        // Enregistrer les timestamps spécifiques
        if ($request->status === 'prete') {
            $commande->preparee_a = now();
        } elseif ($request->status === 'livree') {
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

    private function getTypeActivite($status)
    {
        return match($status) {
            'en_attente' => 'Nouvelle Commande',
            'en_preparation' => 'En Préparation',
            'prete' => 'Commande Prête',
            'en_livraison' => 'En Livraison',
            'livree' => 'Commande Livrée',
            'annulee' => 'Commande Annulée',
            default => 'Commande',
        };
    }

    private function getIconeStatut($status)
    {
        return match($status) {
            'en_attente' => '⏱️',
            'en_preparation' => '👨‍🍳',
            'prete' => '✅',
            'en_livraison' => '🚚',
            'livree' => '🎉',
            'annulee' => '❌',
            default => '📦',
        };
    }

    private function getCouleurStatut($status)
    {
        return match($status) {
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
    
public function statistiques(Request $request)
{
    $periode = $request->get('periode', 'mois'); // jour, semaine, mois, annee
    
    // Déterminer la date de début selon la période
    $dateDebut = match($periode) {
        'jour' => now()->startOfDay(),
        'semaine' => now()->startOfWeek(),
        'mois' => now()->startOfMonth(),
        'annee' => now()->startOfYear(),
        default => now()->startOfMonth(),
    };

    // 1. Commandes totales
    $commandesTotales = Commande::where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->count();

    // 2. Chiffre d'affaires
    
    
    $chiffreAffaires = Commande::where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->sum('montant_total');
    
    // 3. Graphique évolution des ventes
    $evolutionVentes = $this->getEvolutionVentes($dateDebut, $periode);

    // 4. Commandes par jour (pour graphique par jour)
    $commandesParJour = $this->getCommandesParJour($dateDebut, $periode);

    // 5. Top 5 des plats les plus vendus
    $topPlats = $this->getTopPlats($dateDebut);

    return view('employee.statistiques', compact(
        'commandesTotales',
        'chiffreAffaires',
        'evolutionVentes',
        'commandesParJour',
        'topPlats',
        'periode'
    ));
}

private function getEvolutionVentes($dateDebut, $periode)
{
    $format = match($periode) {
        'jour' => '%H', // Heures (0-23)
        'semaine' => '%w', // Jour de la semaine (0-6)
        'mois' => '%d', // Jour du mois (1-31)
        'annee' => '%m', // Mois (1-12)
        default => '%d',
    };

    $labels = match($periode) {
        'jour' => range(0, 23), // 0h à 23h
        'semaine' => ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        'mois' => range(1, now()->daysInMonth),
        'annee' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        default => range(1, now()->daysInMonth),
    };

    $ventes = Commande::select(
            DB::raw("DATE_FORMAT(created_at, '$format') as periode"),
            DB::raw('SUM(montant_total) as total')
        )
        ->where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->groupBy('periode')
        ->pluck('total', 'periode')
        ->toArray();

    // Remplir les données manquantes avec 0
    $data = [];
    foreach ($labels as $key => $label) {
        $index = ($periode === 'semaine') ? $key : ($periode === 'jour' ? $label : $label);
        $data[$label] = isset($ventes[$index]) ? (float)$ventes[$index] : 0;
    }

    return [
        'labels' => $labels,
        'data' => array_values($data),
    ];
}

private function getCommandesParJour($dateDebut, $periode)
{
    // Grouper par jour de la semaine
    $jours = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
    
    $commandes = Commande::select(
            DB::raw('DAYOFWEEK(created_at) as jour'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->groupBy('jour')
        ->pluck('total', 'jour')
        ->toArray();

    $data = [];
    for ($i = 1; $i <= 7; $i++) {
        $data[] = isset($commandes[$i]) ? (int)$commandes[$i] : 0;
    }

    // Réorganiser pour commencer par Lundi
    $reordered = [
        $data[1] ?? 0, // Lun
        $data[2] ?? 0, // Mar
        $data[3] ?? 0, // Mer
        $data[4] ?? 0, // Jeu
        $data[5] ?? 0, // Ven
        $data[6] ?? 0, // Sam
        $data[0] ?? 0, // Dim
    ];

    return [
        'labels' => $jours,
        'data' => $reordered,
    ];
}
private function getTopPlats($dateDebut)
{
    // Utiliser 'status' (nom correct de la colonne)
    $commandes = Commande::where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->get();

    $platsCount = [];

    foreach ($commandes as $commande) {
        // CORRECTION 2: Vérifier si items existe
        if (empty($commande->items)) {
            continue;
        }

        // Décoder les items si c'est une chaîne JSON
        $items = is_string($commande->items) 
            ? json_decode($commande->items, true) 
            : $commande->items;
        
        // CORRECTION 3: Vérifier que le décodage a réussi
        if (!is_array($items) || empty($items)) {
            continue;
        }

        foreach ($items as $item) {
            // CORRECTION 4: Vérifier que les clés existent
            if (!isset($item['nom']) || !isset($item['quantite'])) {
                continue;
            }

            $nom = $item['nom'];
            $prix = $item['prix'] ?? 0;
            $quantite = $item['quantite'] ?? 1;

            if (!isset($platsCount[$nom])) {
                $platsCount[$nom] = [
                    'nom' => $nom,
                    'prix' => $prix,
                    'quantite' => 0,
                    'revenus' => 0,
                ];
            }

            $platsCount[$nom]['quantite'] += $quantite;
            $platsCount[$nom]['revenus'] += ($prix * $quantite);
        }
    }

    // CORRECTION 5: Vérifier qu'il y a des données
    if (empty($platsCount)) {
        return [];
    }

    // Trier par quantité décroissante
    usort($platsCount, function($a, $b) {
        return $b['quantite'] <=> $a['quantite'];
    });

    return array_slice($platsCount, 0, 5);
}

public function exportStatistiques(Request $request)
{
    $periode = $request->get('periode', 'mois');
    
    $dateDebut = match($periode) {
        'jour' => now()->startOfDay(),
        'semaine' => now()->startOfWeek(),
        'mois' => now()->startOfMonth(),
        'annee' => now()->startOfYear(),
        default => now()->startOfMonth(),
    };

    $commandesTotales = Commande::where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->count();

    $chiffreAffaires = Commande::where('created_at', '>=', $dateDebut)
        ->whereNotIn('status', ['annulee'])
        ->sum('montant_total');

    $topPlats = $this->getTopPlats($dateDebut);

    $html = view('employee.statistiques-export', compact(
        'commandesTotales',
        'chiffreAffaires',
        'topPlats',
        'periode',
        'dateDebut'
    ))->render();

    return response($html)
        ->header('Content-Type', 'text/html')
        ->header('Content-Disposition', 'attachment; filename="statistiques-' . now()->format('Y-m-d') . '.html"');
}
}