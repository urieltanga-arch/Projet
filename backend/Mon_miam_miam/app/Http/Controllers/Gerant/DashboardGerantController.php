<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;
use App\Models\Reclamation;
use Carbon\Carbon;

class DashboardGerantController extends Controller
{
    public function index(Request $request)
    {
        // Déterminer la période (jour, semaine, mois)
        $periode = $request->get('periode', 'jour');
        
        // Calcul des dates selon la période
        switch($periode) {
            case 'semaine':
                $dateDebut = Carbon::now()->startOfWeek();
                $dateFin = Carbon::now()->endOfWeek();
                break;
            case 'mois':
                $dateDebut = Carbon::now()->startOfMonth();
                $dateFin = Carbon::now()->endOfMonth();
                break;
            default: // jour
                $dateDebut = Carbon::today();
                $dateFin = Carbon::now();
        }

        // Commandes actives (en cours)
        $commandesActives = Commande::whereIn('status', ['en_attente', 'en_preparation', 'prete'])->count();

        // Revenu de la période
        $revenuPeriode = Commande::where('status', 'livree')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->sum('montant_total');

        // Clients actifs (ayant commandé dans la période)
        $clientsActifs = Commande::whereBetween('created_at', [$dateDebut, $dateFin])
            ->distinct('user_id')
            ->count('user_id');

        // Performance de l'équipe (calcul simplifié)
        $totalCommandes = Commande::whereBetween('created_at', [$dateDebut, $dateFin])->count();
        $commandesLivrees = Commande::where('status', 'livree')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();
        $performanceEquipe = $totalCommandes > 0 ? round(($commandesLivrees / $totalCommandes) * 100, 1) : 0;

        // Commandes en temps réel
        $commandesTempsReel = Commande::whereIn('status', ['en_attente', 'en_preparation', 'prete'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Équipe en service
        $equipeEnService = User::whereIn('role', ['employee', 'admin'])
            ->where('is_suspended', false)
            ->limit(4)
            ->get();

        // Alertes (réclamations non traitées)
        $alertes = Reclamation::where('statut', 'non_traitee')
            ->orderBy('created_at', 'desc')
            ->get();
        $nombreAlertes = $alertes->count();

        // Performances journalières pour le graphique
        $performancesJournalieres = $this->getPerformanceData($periode);

        return view('gerant.dashboard', compact(
            'periode',
            'commandesActives',
            'revenuPeriode', 
            'clientsActifs',
            'performanceEquipe',
            'commandesTempsReel',
            'equipeEnService',
            'alertes',
            'nombreAlertes',
            'performancesJournalieres'
        ));
    }

    private function getPerformanceData($periode)
    {
        $data = [
            'labels' => [],
            'commandes' => [],
            'revenus' => []
        ];

        switch($periode) {
            case 'semaine':
                // Données pour les 7 derniers jours
                for($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $data['labels'][] = $date->format('D');
                    
                    $commandesCount = Commande::whereDate('created_at', $date)->count();
                    $revenus = Commande::where('status', 'livree')
                        ->whereDate('created_at', $date)
                        ->sum('montant_total');
                    
                    $data['commandes'][] = $commandesCount;
                    $data['revenus'][] = $revenus;
                }
                break;

            case 'mois':
                // Données pour les 4 dernières semaines
                for($i = 3; $i >= 0; $i--) {
                    $startWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    $data['labels'][] = 'S' . ($startWeek->weekOfYear);
                    
                    $commandesCount = Commande::whereBetween('created_at', [$startWeek, $endWeek])->count();
                    $revenus = Commande::where('status', 'livree')
                        ->whereBetween('created_at', [$startWeek, $endWeek])
                        ->sum('montant_total');
                    
                    $data['commandes'][] = $commandesCount;
                    $data['revenus'][] = $revenus;
                }
                break;

            default: // jour
                // Données pour les 12 dernières heures
                for($i = 11; $i >= 0; $i--) {
                    $hour = Carbon::now()->subHours($i);
                    $data['labels'][] = $hour->format('H:00');
                    
                    $startHour = $hour->copy()->startOfHour();
                    $endHour = $hour->copy()->endOfHour();
                    
                    $commandesCount = Commande::whereBetween('created_at', [$startHour, $endHour])->count();
                    $revenus = Commande::where('status', 'livree')
                        ->whereBetween('created_at', [$startHour, $endHour])
                        ->sum('montant_total');
                    
                    $data['commandes'][] = $commandesCount;
                    $data['revenus'][] = $revenus;
                }
        }

        return $data;
    }
}