<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiquesController extends Controller
{
    public function index(Request $request)
    {
        // Période par défaut : 30 derniers jours
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Conversion en objets Carbon
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 1. Évolution des ventes (par jour)
        $evolutionVentes = DB::table('commandes')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as nombre_commandes'),
                DB::raw('SUM(montant_total) as total_ventes')
            )
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['livree', 'en_livraison', 'prete'])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 2. Répartition par catégorie
        $repartitionCategorie = DB::table('commande_items')
            ->join('commandes', 'commande_items.commande_id', '=', 'commandes.id')
            ->join('plats', 'commande_items.plat_id', '=', 'plats.id')
            ->select(
                'plats.category',
                DB::raw('COUNT(commande_items.id) as total_items'),
                DB::raw('SUM(commande_items.quantity * COALESCE(commande_items.price, plats.price)) as total_ventes')
            )
            ->whereBetween('commandes.created_at', [$start, $end])
            ->whereIn('commandes.status', ['livree', 'en_livraison', 'prete'])
            ->groupBy('plats.category')
            ->get();

        // 3. Analyse Fidélité
        $clientsFideles = DB::table('users')
            ->where('total_points', '>', 0)
            ->count();

        $pointsDistribues = DB::table('commandes')
            ->whereBetween('created_at', [$start, $end])
            ->sum('points_gagnes');

        $tauxRetention = DB::table('users')
            ->join('commandes', 'users.id', '=', 'commandes.user_id')
            ->select('users.id')
            ->whereBetween('commandes.created_at', [$start, $end])
            ->groupBy('users.id')
            ->havingRaw('COUNT(commandes.id) > 1')
            ->get()
            ->count();

        $totalClients = DB::table('users')
            ->join('commandes', 'users.id', '=', 'commandes.user_id')
            ->whereBetween('commandes.created_at', [$start, $end])
            ->distinct('users.id')
            ->count('users.id');

        $tauxRetentionPourcent = $totalClients > 0 ? round(($tauxRetention / $totalClients) * 100) : 0;

        // 4. Programme de Fidélité (répartition des points)
        $pointsGagnes = DB::table('commandes')
            ->whereBetween('created_at', [$start, $end])
            ->sum('points_gagnes');

        $pointsUtilises = DB::table('loyalty_points')
            ->where('type', 'spent')
            ->whereBetween('created_at', [$start, $end])
            ->sum('points');

        $bonusParrainage = DB::table('referrals')
            ->whereBetween('created_at', [$start, $end])
            ->sum('points_earned');

        // 5. Système de Parrainage (évolution mensuelle)
        $parrainageData = DB::table('referrals')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total_parrainages'),
                DB::raw('COUNT(DISTINCT referrer_id) as parrains_actifs')
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->get();

        // 6. Clients les plus fidèles
        $topClients = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.total_points',
                DB::raw('COUNT(commandes.id) as nombre_commandes'),
                DB::raw('SUM(commandes.montant_total) as total_depense')
            )
            ->join('commandes', 'users.id', '=', 'commandes.user_id')
            ->whereBetween('commandes.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name', 'users.total_points')
            ->orderBy('users.total_points', 'desc')
            ->limit(5)
            ->get();

        return view('admin.statistiques', compact(
            'evolutionVentes',
            'repartitionCategorie',
            'clientsFideles',
            'pointsDistribues',
            'tauxRetentionPourcent',
            'pointsGagnes',
            'pointsUtilises',
            'bonusParrainage',
            'parrainageData',
            'topClients',
            'startDate',
            'endDate'
        ));
    }
}