<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use App\Models\Plat;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardGerantController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'jour'); // jour, semaine, mois
        
        // Déterminer la plage de dates
        $dateDebut = match($periode) {
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfDay(),
        };
        
        $dateFin = Carbon::now();

        // 1. Commandes actives (en_attente, en_preparation, prete)
        $commandesActives = Commande::whereIn('status', ['en_attente', 'en_preparation', 'prete'])
            ->count();

        // 2. Revenu du jour/période
        $revenuPeriode = Commande::where('status', 'livree')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->sum('montant_total');

        // 3. Clients actifs (qui ont commandé dans la période)
        $clientsActifs = Commande::whereBetween('created_at', [$dateDebut, $dateFin])
            ->distinct('user_id')
            ->count('user_id');

        // 4. Performance équipe (% de commandes livrées vs total)
        $totalCommandes = Commande::whereBetween('created_at', [$dateDebut, $dateFin])->count();
        $commandesLivrees = Commande::where('status', 'livree')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->count();
        
        $performanceEquipe = $totalCommandes > 0 ? round(($commandesLivrees / $totalCommandes) * 100) : 0;

        // 5. Commandes en temps réel (dernières 4 commandes actives)
        $commandesTempsReel = Commande::with(['user', 'items.plat'])
            ->whereIn('status', ['en_attente', 'en_preparation', 'prete'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // 6. Performances journalières (graphique)
        $performancesJournalieres = $this->getPerformancesJournalieres($periode);

        // 7. Équipe en service (employés)
        $equipeEnService = User::whereIn('role', ['employee', 'admin'])
            ->where('is_suspended', 0)
            ->limit(4)
            ->get();

        // 8. Alertes importantes (réclamations non traitées)
        $alertes = Reclamation::with('commande')
            ->where('statut', 'non_traitee')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $nombreAlertes = $alertes->count();

        return view('gerant.dashboard', compact(
            'commandesActives',
            'revenuPeriode',
            'clientsActifs',
            'performanceEquipe',
            'commandesTempsReel',
            'performancesJournalieres',
            'equipeEnService',
            'alertes',
            'nombreAlertes',
            'periode'
        ));
    }

    private function getPerformancesJournalieres($periode)
    {
        $format = match($periode) {
            'mois' => '%Y-%m-%d',
            'semaine' => '%Y-%m-%d',
            default => '%H:00',
        };

        $dateDebut = match($periode) {
            'mois' => Carbon::now()->subDays(30),
            'semaine' => Carbon::now()->subDays(7),
            default => Carbon::now()->startOfDay(),
        };

        $donnees = Commande::select(
                DB::raw("DATE_FORMAT(created_at, '$format') as periode"),
                DB::raw('COUNT(*) as total_commandes'),
                DB::raw('SUM(CASE WHEN status = "livree" THEN montant_total ELSE 0 END) as revenus')
            )
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

        return [
            'labels' => $donnees->pluck('periode')->toArray(),
            'commandes' => $donnees->pluck('total_commandes')->toArray(),
            'revenus' => $donnees->pluck('revenus')->toArray(),
        ];
    }
}