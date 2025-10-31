<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GerantEmployeeManagerController extends Controller
{
    /**
     * Afficher la liste des employés
     */
    public function index()
    {
        $employees = User::whereIn('role', ['employee', 'manager'])
            ->orderBy('name')
            ->get();
        
        return view('gerant.employees.index', compact('employees'));
    }
    
    /**
     * Ajouter un nouvel employé
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:employee,manager',
        ]);
        
        // Créer l'employé avec un mot de passe par défaut
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make('password123'), // Mot de passe par défaut
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Employé ajouté avec succès ! Mot de passe par défaut: password123',
            'employee' => $user
        ]);
    }
    
    /**
     * Mettre à jour un employé
     */
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:employee,manager,admin',
        ]);
        
        $employee->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Employé mis à jour avec succès'
        ]);
    }
    
    /**
     * Suspendre/Activer un employé
     */
    public function toggleStatus($id)
    {
        $employee = User::findOrFail($id);
        
        // Toggle le statut (actif/suspendu)
        $employee->is_suspended = !($employee->is_suspended ?? false);
        $employee->save();
        
        $message = $employee->is_suspended 
            ? 'Employé suspendu avec succès' 
            : 'Employé réactivé avec succès';
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_suspended' => $employee->is_suspended
        ]);
    }
    
    /**
     * Supprimer un employé
     */
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        
        // Empêcher la suppression de l'admin connecté
        if ($employee->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ], 400);
        }
        
        // Empêcher la suppression d'un admin
        if ($employee->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer un administrateur'
            ], 400);
        }
        
        $employee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Employé supprimé avec succès'
        ]);
    }
}