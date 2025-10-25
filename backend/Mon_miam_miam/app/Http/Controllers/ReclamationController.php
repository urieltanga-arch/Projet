<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Commande;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    /**
     * Afficher la liste des réclamations
     */
    public function index()
    {
        $reclamations = Reclamation::with(['commande.user'])
            ->latest()
            ->paginate(10);

        // Statistiques
        $totalReclamations = Reclamation::count();
        $enAttenteCount = Reclamation::where('statut', 'en_cours')->count();
        $traitesCount = Reclamation::where('statut', 'resolue')->count();

        return view('reclamations.index', compact(
            'reclamations',
            'totalReclamations',
            'enAttenteCount',
            'traitesCount'
        ));
    }

    /**
     * Afficher les détails d'une réclamation (pour AJAX)
     */
    public function show($id)
    {
        $reclamation = Reclamation::with('commande.user')->findOrFail($id);
        
        return response()->json([
            'id' => $reclamation->id,
            'commande_id' => $reclamation->commande_id,
            'type_probleme' => $reclamation->type_probleme,
            'description' => $reclamation->description,
            'reponse_employee' => $reclamation->reponse_employee,
            'statut' => $this->mapStatut($reclamation->statut),
            'created_at' => $reclamation->created_at,
            'updated_at' => $reclamation->updated_at,
        ]);
    }

    /**
     * Mettre à jour le statut d'une réclamation
     */
    public function updateStatus(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);
        
        $statutDemande = $request->input('statut');
        
        // Mapper l'ancien format vers le nouveau
        $nouveauStatut = match($statutDemande) {
            'Traité' => 'resolue',
            'En attente' => 'en_cours',
            'Total' => 'non_traitee',
            default => $statutDemande // Si déjà au bon format
        };
        
        $reclamation->update([
            'statut' => $nouveauStatut
        ]);

        return redirect()->route('reclamations.index')
            ->with('success', 'Le statut de la réclamation a été mis à jour avec succès.');
    }

    /**
     * Supprimer une réclamation
     */
    public function destroy($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->delete();

        return redirect()->route('reclamations.index')
            ->with('success', 'La réclamation a été supprimée avec succès.');
    }

    /**
     * Mapper les statuts de l'ancien format vers le nouveau
     */
    private function mapStatut($statut)
    {
        return match($statut) {
            'non_traitee' => 'Total',
            'en_cours' => 'En attente',
            'resolue' => 'Traité',
            'fermee' => 'Fermée',
            default => $statut
        };
    }
}