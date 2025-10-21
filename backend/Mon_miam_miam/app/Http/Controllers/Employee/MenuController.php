<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Afficher tous les plats
     */
    public function index()
    {
        $plats = Plat::orderBy('category')->orderBy('name')->get();
        
        // Grouper par catégorie
        $platsPrincipaux = $plats->where('category', 'plat');
        $boissons = $plats->where('category', 'boisson');
        $desserts = $plats->whereIn('category', ['dessert', 'desserts']);
        
        return view('employee.menu.index', compact('plats', 'platsPrincipaux', 'boissons', 'desserts'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('employee.menu.create');
    }

    /**
     * Enregistrer un nouveau plat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:plat,boisson,dessert',
            'image_url' => 'nullable|url',
            'is_available' => 'boolean'
        ]);

        $validated['is_available'] = $request->has('is_available') ? 1 : 0;

        Plat::create($validated);

        return redirect()->route('employee.menu.index')
            ->with('success', 'Plat ajouté avec succès !');
    }

    /**
     * Formulaire de modification
     */
    public function edit(Plat $plat)
    {
        return view('employee.menu.edit', compact('plat'));
    }

    /**
     * Mettre à jour un plat
     */
    public function update(Request $request, Plat $plat)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:plat,boisson,dessert',
            'image_url' => 'nullable|url',
            'is_available' => 'boolean'
        ]);

        $validated['is_available'] = $request->has('is_available') ? 1 : 0;

        $plat->update($validated);

        return redirect()->route('employee.menu.index')
            ->with('success', 'Plat modifié avec succès !');
    }

    /**
     * Basculer la disponibilité (toggle)
     */
    public function toggleAvailability(Plat $plat)
    {
        $plat->update([
            'is_available' => !$plat->is_available
        ]);

        return response()->json([
            'success' => true,
            'is_available' => $plat->is_available
        ]);
    }

    /**
     * Supprimer un plat
     */
    public function destroy(Plat $plat)
    {
        $plat->delete();

        return redirect()->route('employee.menu.index')
            ->with('success', 'Plat supprimé avec succès !');
    }
}