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
        $periode = $request->get('periode', 'jour');
        
        // Filtrer par période
        $dateDebut = match($periode) {
            'jour' => Carbon::today(),
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
        };
        
        $reclamations = Reclamation::with(['user'])
            ->where('created_at', '>=', $dateDebut)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'total' => $reclamations->count(),
            'en_attente' => $reclamations->where('status', 'pending')->count(),
            'en_traitement' => $reclamations->where('status', 'in_progress')->count(),
            'traitees' => $reclamations->where('status', 'resolved')->count(),
            'resolues' => Reclamation::where('status', 'resolved')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];
        
        return view('gerant.reclamations.index', compact('reclamations', 'stats', 'periode'));
    
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
         $periode = $request->get('periode', 'jour');
    
    // Filtrer par période
    $dateDebut = match($periode) {
        'jour' => now()->startOfDay(),
        'semaine' => now()->startOfWeek(),
        'mois' => now()->startOfMonth(),
    };
    
    $reclamations = Reclamation::with(['client', 'employe'])
        ->where('created_at', '>=', $dateDebut)
        ->orderBy('priorite', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
    
    $stats = [
        'total' => $reclamations->count(),
        'en_attente' => $reclamations->where('statut', 'en_attente')->count(),
        'en_traitement' => $reclamations->where('statut', 'en_traitement')->count(),
        'traitees' => $reclamations->where('statut', 'traitee')->count(),
        'resolues' => Reclamation::where('statut', 'resolue')
            ->whereMonth('created_at', now()->month)
            ->count(),
    ];
    
    return view('admin.reclamations.index', compact('reclamations', 'stats', 'periode'));

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



 

    public function valider($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update(['status' => 'in_progress']);
        
        return redirect()->back()->with('success', 'Réclamation validée');
    }

    public function resoudre($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update(['status' => 'resolved']);
        
        return redirect()->back()->with('success', 'Réclamation résolue');
    }

    public function edit($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        return view('gerant.reclamations.edit', compact('reclamation'));
    }
}
