<?php

namespace App\Http\Controllers\Gerant;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use App\Models\Commande;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GerantReclamationsController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'jour');
        $search = $request->get('search', '');
        
        // Filtrer par période
        $dateDebut = match($periode) {
            'jour' => Carbon::today(),
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
        };
        
        // Récupérer toutes les réclamations
        $reclamations = Reclamation::with(['commande.user'])
            ->where('created_at', '>=', $dateDebut)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Compter tous les statuts possibles de manière dynamique
        $statutsCount = Reclamation::where('created_at', '>=', $dateDebut)
            ->select('statut', \DB::raw('count(*) as count'))
            ->groupBy('statut')
            ->pluck('count', 'statut');
        
        // Créer les stats avec des valeurs par défaut à 0
        $stats = [
            'total' => $reclamations->count(),
            'en_attente' => $statutsCount['en_cours'] ?? $statutsCount['pending'] ?? 0,
            'en_traitement' => $statutsCount['en_cours'] ?? $statutsCount['in_progress'] ?? 0,
            'traitees' => $statutsCount['resolue'] ?? $statutsCount['resolved'] ?? 0,
            'resolues' => Reclamation::where('statut', 'resolue')
                ->orWhere('statut', 'resolved')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];
        
        return view('gerant.reclamations.index', compact('reclamations', 'stats', 'periode','search'));
    }

    public function show($id)
    {
        $reclamation = Reclamation::with('commande.user')->findOrFail($id);
        
        return response()->json([
            'id' => $reclamation->id,
            'commande_id' => $reclamation->commande_id,
            'type_probleme' => $reclamation->type_probleme,
            'description' => $reclamation->description,
            'reponse_employee' => $reclamation->reponse_employee,
            'statut' => $reclamation->statut,
            'created_at' => $reclamation->created_at,
            'updated_at' => $reclamation->updated_at,
        ]);
    }

    public function valider($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update(['statut' => 'en_cours']);
        
        return redirect()->back()->with('success', 'Réclamation validée');
    }

    public function resoudre($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->update(['statut' => 'resolue']);
        
        return redirect()->back()->with('success', 'Réclamation résolue');
    }

    public function edit($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        return view('gerant.reclamations.edit', compact('reclamation'));
    }

    public function updateStatus(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);
        
        $reclamation->update([
            'statut' => $request->input('statut')
        ]);

        return redirect()->route('gerant.reclamations.index')
            ->with('success', 'Le statut de la réclamation a été mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->delete();

        return redirect()->route('gerant.reclamations.index')
            ->with('success', 'La réclamation a été supprimée avec succès.');
    }
}