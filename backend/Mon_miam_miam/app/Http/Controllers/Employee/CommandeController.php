<?php

namespace App\Http\Controllers\Employee;

use App\Models\Commande;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Afficher toutes les commandes de l'utilisateur
     */
    public function index()
    {
        $commandes = auth()->user()
            ->commandes()
            ->with('items.plat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('commandes.index', compact('commandes'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }

        $commande->load('items.plat');

        return view('commandes.show', compact('commande'));
    }

    /**
     * Annuler une commande
     */
    public function cancel(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }

        // On peut seulement annuler si la commande est en attente
        if ($commande->status === 'en_attente') {
            $commande->update(['status' => 'annulee']);
            return redirect()->back()->with('success', 'Commande annulée avec succès');
        }

        return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée');
    }
}