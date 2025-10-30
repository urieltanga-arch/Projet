<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\User;
use App\Models\Plat;
use App\Models\Reclamation;
use Carbon\Carbon;

class GerantController extends Controller
{
    /**
     * Afficher les statistiques
     */
    public function statistics(Request $request)
    {
        $periode = $request->get('periode', 'semaine');
        
        // Définir la date de début selon la période
        $dateDebut = match($periode) {
            'jour' => Carbon::today(),
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfWeek(),
        };

        // Statistiques générales
        $totalCommandes = Commande::where('created_at', '>=', $dateDebut)->count();
        $commandesEnCours = Commande::whereIn('status', ['en_attente', 'en_preparation', 'prete'])->count();
        $commandesLivrees = Commande::whereNotNull('livree_a')
            ->where('created_at', '>=', $dateDebut)
            ->count();
        
        $revenuTotal = Commande::whereNotNull('livree_a')
            ->where('created_at', '>=', $dateDebut)
            ->sum('montant_total');
        
        $revenuMoyen = $totalCommandes > 0 ? $revenuTotal / $totalCommandes : 0;
        
        // Nombre de clients actifs
        $clientsActifs = User::where('role', 'student')
            ->whereHas('commandes', function($query) use ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut);
            })
            ->count();
        
        // Taux de satisfaction (basé sur les commandes sans réclamation)
        $commandesAvecReclamation = Reclamation::where('created_at', '>=', $dateDebut)->count();
        $tauxSatisfaction = $totalCommandes > 0 
            ? round((($totalCommandes - $commandesAvecReclamation) / $totalCommandes) * 100, 1)
            : 100;

        // Plats les plus vendus - Temporairement désactivé en attendant la structure correcte
        $platsPopulaires = collect([]);

        // Performance par jour (pour le graphique)
        $performanceJours = [];
        if ($periode === 'semaine') {
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->startOfWeek()->addDays($i);
                $performanceJours[] = [
                    'jour' => $date->format('D'),
                    'commandes' => Commande::whereDate('created_at', $date)->count(),
                    'revenu' => Commande::whereDate('created_at', $date)
                        ->where('status', 'livree')
                        ->sum('montant_total')
                ];
            }
        } elseif ($periode === 'mois') {
            $semaines = 4;
            for ($i = 0; $i < $semaines; $i++) {
                $dateDebut = Carbon::now()->startOfMonth()->addWeeks($i);
                $dateFin = $dateDebut->copy()->endOfWeek();
                $performanceJours[] = [
                    'jour' => 'S' . ($i + 1),
                    'commandes' => Commande::whereBetween('created_at', [$dateDebut, $dateFin])->count(),
                    'revenu' => Commande::whereBetween('created_at', [$dateDebut, $dateFin])
                        ->where('status', 'livree')
                        ->sum('montant_total')
                ];
            }
        } else {
            // Pour la journée, afficher par heures
            for ($i = 8; $i <= 20; $i++) {
                $performanceJours[] = [
                    'jour' => $i . 'h',
                    'commandes' => Commande::whereDate('created_at', today())
                        ->whereHour('created_at', $i)
                        ->count(),
                    'revenu' => Commande::whereDate('created_at', today())
                        ->whereHour('created_at', $i)
                        ->where('status', 'livree')
                        ->sum('montant_total')
                ];
            }
        }

        // Top clients
        $topClients = User::where('role', 'student')
            ->withCount(['commandes' => function($query) use ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut);
            }])
            ->withSum(['commandes' => function($query) use ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut)
                      ->where('status', 'livree');
            }], 'montant_total')
            ->having('commandes_count', '>', 0)
            ->orderByDesc('commandes_count')
            ->limit(5)
            ->get();

        // Statistiques des employés
        $performanceEmployes = User::where('role', 'employee')
            ->withCount(['commandesTraitees' => function($query) use ($dateDebut) {
                $query->where('updated_at', '>=', $dateDebut);
            }])
            ->orderByDesc('commandes_traitees_count')
            ->get();

        // Réclamations récentes
        $reclamationsRecentes = Reclamation::with(['client', 'employe'])
            ->where('created_at', '>=', $dateDebut)
            ->latest()
            ->limit(5)
            ->get();

        return view('gerant.statistics', compact(
            'periode',
            'totalCommandes',
            'commandesEnCours',
            'commandesLivrees',
            'revenuTotal',
            'revenuMoyen',
            'clientsActifs',
            'tauxSatisfaction',
            'platsPopulaires',
            'performanceJours',
            'topClients',
            'performanceEmployes',
            'reclamationsRecentes'
        ));
    }

    /**
     * Gestion des employés
     */
    public function employees()
    {
        $employees = User::where('role', 'employee')
            ->withCount('commandesTraitees')
            ->orderBy('name')
            ->get();

        return view('gerant.employees.index', compact('employees'));
    }

    public function createEmployee()
    {
        return view('gerant.employees.create');
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé créé avec succès');
    }

    public function editEmployee(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403, 'Action non autorisée');
        }

        return view('gerant.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403, 'Action non autorisée');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        $employee->update($data);

        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé modifié avec succès');
    }

    public function deleteEmployee(User $employee)
    {
        if ($employee->role !== 'employee') {
            abort(403, 'Action non autorisée');
        }

        $employee->delete();

        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé supprimé avec succès');
    }
}