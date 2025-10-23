<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeEmployeeController extends Controller
{
    /**
     * Afficher toutes les commandes (interface employé)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Commande::with(['user', 'items.plat'])
            ->orderBy('created_at', 'desc');
        
        // Filtrer par statut si nécessaire
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $commandes = $query->get();
        
        // Grouper les commandes par statut
        $nouvelles = $commandes->where('status', 'en_attente');
        $enPreparation = $commandes->where('status', 'en_preparation');
        $pretes = $commandes->where('status', 'prete');
        $livrees = $commandes->where('status', 'livree');
        
        return view('employee.commandes.index', compact(
            'commandes',
            'nouvelles',
            'enPreparation',
            'pretes',
            'livrees',
            'status'
        ));
    }
    
    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load(['user', 'items.plat']);
        return view('employee.commandes.show', compact('commande'));
    }
    
    /**
     * Changer le statut d'une commande
     */
    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:en_attente,en_preparation,prete,livree,annulee'
        ]);
        
        $commande->update([
            'status' => $request->status
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);
    }
    
    /**
     * Ajouter une note à la commande
     */
    public function addNote(Request $request, Commande $commande)
    {
        $request->validate([
            'note' => 'required|string|max:500'
        ]);
        
        $existingNotes = $commande->notes ?? '';
        $newNote = "[" . now()->format('H:i') . "] " . $request->note;
        
        $commande->update([
            'notes' => $existingNotes . "\n" . $newNote
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Note ajoutée avec succès'
        ]);
    }
    
    /**
     * Annuler une commande
     */
    public function cancel(Commande $commande)
    {
        $commande->update(['status' => 'annulee']);
        
        return redirect()->back()->with('success', 'Commande annulée');
    }
    
    /**
     * Obtenir les commandes récentes (pour refresh auto)
     */
    public function refresh(Request $request)
    {
        $lastUpdate = $request->get('last_update');
        
        $commandes = Commande::with(['user', 'items.plat'])
            ->when($lastUpdate, function($query) use ($lastUpdate) {
                return $query->where('updated_at', '>', $lastUpdate);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'commandes' => $commandes,
            'counts' => [
                'nouvelles' => Commande::where('status', 'en_attente')->count(),
                'en_preparation' => Commande::where('status', 'en_preparation')->count(),
                'pretes' => Commande::where('status', 'prete')->count(),
                'livrees' => Commande::where('status', 'livree')->count(),
            ]
        ]);
    }
}