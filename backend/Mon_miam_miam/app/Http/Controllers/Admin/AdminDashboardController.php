<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'jour');
        
        // Définir la période
        $dateDebut = match($periode) {
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
            'jour' => Carbon::now()->startOfDay(),
            default => Carbon::now()->startOfDay(),
        };
        
        // 1. STATISTIQUES PRINCIPALES
        $ventesAujourdhui = Commande::where('created_at', '>=', $dateDebut)
            ->whereIn('status', ['prete', 'livree'])
            ->sum('montant_total');
        
        $nombreCommandes = Commande::where('created_at', '>=', $dateDebut)->count();
        
        $clientsActifs = Commande::where('created_at', '>=', $dateDebut)
            ->distinct('user_id')
            ->count('user_id');
        
        $pointsAttribues = User::sum('total_points');
        
        // 2. GRAPHIQUE DES VENTES
        $graphiqueData = $this->getGraphiqueVentes($periode);
        
        // 3. ACTIVITÉS RÉCENTES
        $activitesRecentes = $this->getActivitesRecentes();
        
        // 4. ALERTES SYSTÈME
        $platsDisponibles = Plat::where('is_available', true)->count();
        $platsIndisponibles = Plat::where('is_available', false)->count();
        $commandesEnAttente = Commande::where('status', 'en_attente')
            ->where('created_at', '<', Carbon::now()->subMinutes(30))
            ->count();
        
        $alertes = [];
        
        if ($platsIndisponibles > 0) {
            $alertes[] = [
                'type' => 'stock',
                'message' => "Stock Faible : Eru ($platsIndisponibles plats)",
                'couleur' => 'red',
                'icone' => '⚠️'
            ];
        }
        
        if ($commandesEnAttente > 0) {
            $alertes[] = [
                'type' => 'retard',
                'message' => "Commande en retard : *1230 ($commandesEnAttente en retard)",
                'couleur' => 'yellow',
                'icone' => '⏰'
            ];
        }
        
        return view('admin.dashboard', compact(
            'periode',
            'ventesAujourdhui',
            'nombreCommandes',
            'clientsActifs',
            'pointsAttribues',
            'graphiqueData',
            'activitesRecentes',
            'alertes',
            'platsDisponibles'
        ));
    }
    
    /**
     * Générer les données du graphique
     */
    private function getGraphiqueVentes($periode)
    {
        $labels = [];
        $ventes = [];
        
        switch($periode) {
            case 'semaine':
                // Derniers 7 jours
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $montant = Commande::whereDate('created_at', $date)
                        ->whereIn('status', ['prete', 'livree'])
                        ->sum('montant_total');
                    $ventes[] = $montant;
                }
                break;
                
            case 'mois':
                // Dernières 4 semaines
                for ($i = 3; $i >= 0; $i--) {
                    $debut = Carbon::now()->subWeeks($i)->startOfWeek();
                    $fin = Carbon::now()->subWeeks($i)->endOfWeek();
                    $labels[] = 'S' . $debut->weekOfYear;
                    
                    $montant = Commande::whereBetween('created_at', [$debut, $fin])
                        ->whereIn('status', ['prete', 'livree'])
                        ->sum('montant_total');
                    $ventes[] = $montant;
                }
                break;
                
            default: // jour
                // Dernières 24 heures par tranches de 4h
                for ($i = 20; $i >= 0; $i -= 4) {
                    $heure = Carbon::now()->subHours($i);
                    $labels[] = $heure->format('H:i');
                    
                    $montant = Commande::where('created_at', '>=', $heure)
                        ->where('created_at', '<', $heure->copy()->addHours(4))
                        ->whereIn('status', ['prete', 'livree'])
                        ->sum('montant_total');
                    $ventes[] = $montant;
                }
                break;
        }
        
        return [
            'labels' => $labels,
            'ventes' => $ventes
        ];
    }
    
    /**
     * Récupérer les activités récentes
     */
    private function getActivitesRecentes()
    {
        $activites = [];
        
        // Nouvelles commandes
        $nouvellesCommandes = Commande::with('user')
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($nouvellesCommandes as $commande) {
            $activites[] = [
                'type' => 'commande',
                'message' => "Nouvelle commande *{$commande->id}",
                'details' => $commande->user->name,
                'temps' => $commande->created_at->diffForHumans(),
                'icone' => '🛒',
                'couleur' => 'yellow'
            ];
        }
        
        // Nouveaux clients
        $nouveauxClients = User::where('role', 'student')
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($nouveauxClients as $user) {
            $activites[] = [
                'type' => 'client',
                'message' => 'Nouveau client inscrit',
                'details' => $user->name,
                'temps' => $user->created_at->diffForHumans(),
                'icone' => '👤',
                'couleur' => 'blue'
            ];
        }
        
        // Points fidélité (simulation basée sur les commandes récentes)
        $commandesAvecPoints = Commande::with('user')
            ->where('points_gagnes', '>', 0)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->take(2)
            ->get();
        
        foreach ($commandesAvecPoints as $commande) {
            $activites[] = [
                'type' => 'points',
                'message' => 'Points fidélité attribués',
                'details' => $commande->user->name,
                'temps' => $commande->created_at->diffForHumans(),
                'icone' => '⭐',
                'couleur' => 'green'
            ];
        }
        
        // Alertes stock (plats récemment indisponibles)
        $platsIndisponibles = Plat::where('is_available', false)
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->latest('updated_at')
            ->take(2)
            ->get();
        
        foreach ($platsIndisponibles as $plat) {
            $activites[] = [
                'type' => 'stock',
                'message' => 'Alerte Stock faible',
                'details' => $plat->name,
                'temps' => $plat->updated_at->diffForHumans(),
                'icone' => '⚠️',
                'couleur' => 'red'
            ];
        }
        
        // Trier par date et prendre les 10 derniers
        usort($activites, function($a, $b) {
            return strcmp($b['temps'], $a['temps']);
        });
        
        return array_slice($activites, 0, 10);
    }
}