<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GerantController extends Controller
{
    // Statistiques
    public function statistics()
    {
        // Statistiques des commandes
        $commandesStats = [
            'total' => Commande::count(),
            'today' => Commande::whereDate('created_at', Carbon::today())->count(),
            'week' => Commande::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'month' => Commande::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        // Chiffre d'affaires
        $revenueStats = [
            'total' => Commande::sum('total'),
            'today' => Commande::whereDate('created_at', Carbon::today())->sum('total'),
            'week' => Commande::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total'),
            'month' => Commande::whereMonth('created_at', Carbon::now()->month)->sum('total'),
        ];

        // Plats les plus populaires
        $topPlats = Plat::withCount('commandeItems')
            ->orderByDesc('commande_items_count')
            ->take(5)
            ->get();

        // Évolution des commandes sur les 7 derniers jours
        $dailyOrders = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->take(7)
            ->get();

        return view('gerant.statistics', compact(
            'commandesStats',
            'revenueStats',
            'topPlats',
            'dailyOrders'
        ));
    }

    // Gestion des employés
    public function employees()
    {
        $employees = User::where('role', 'employee')->get();
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'employee',
        ]);

        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé ajouté avec succès');
    }

    public function editEmployee(User $employee)
    {
        return view('gerant.employees.edit', compact('employee'));
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'phone' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if ($validated['password']) {
            $employee->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé mis à jour avec succès');
    }

    public function deleteEmployee(User $employee)
    {
        $employee->delete();
        return redirect()->route('gerant.employees.index')
            ->with('success', 'Employé supprimé avec succès');
    }
}