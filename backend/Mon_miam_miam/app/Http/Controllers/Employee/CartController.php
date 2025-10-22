<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller; 
use App\Models\Plat;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $montant_total = 0;
        
        foreach ($cart as $item) {
            $montant_total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'montant_total'));
        
    }

    /**
     * Ajouter un plat au panier
     */
    public function add(Request $request, $platId)
    {
        $plat = Plat::findOrFail($platId);
        
        $cart = session()->get('cart', []);
        
        // Si le plat existe déjà dans le panier, augmenter la quantité
        if (isset($cart[$platId])) {
            $cart[$platId]['quantity']++;
        } else {
            // Ajouter le nouveau plat
            $cart[$platId] = [
                'id' => $plat->id,
                'name' => $plat->name,
                'price' => $plat->price,
                'image_url' => $plat->image_url,
                'quantity' => 1,
                'points' => $plat->points ?? 0
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Plat ajouté au panier !',
            'cartCount' => $this->getCartCount()
        ]);
    }

    /**
     * Retirer un plat du panier
     */
    public function remove($platId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$platId])) {
            unset($cart[$platId]);
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Plat retiré du panier',
            'cartCount' => $this->getCartCount()
        ]);
        
    }

    /**
     * Mettre à jour la quantité d'un plat
     */
    public function updateQuantity(Request $request, $platId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$platId])) {
            $quantity = max(1, (int)$request->quantity);
            $cart[$platId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'cartCount' => $this->getCartCount()
        ]);
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        session()->forget('cart');
        
        return redirect()->back()->with('success', 'Panier vidé');
    }

    /**
     * Obtenir le nombre total d'articles dans le panier
     */
    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    /**
     * Valider la commande
     */
    public function checkout(Request $request)
{
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Votre panier est vide'
        ], 400);
    }
    
    // Valider les données du formulaire
    $validated = $request->validate([
        'adresse_livraison' => 'required|string|max:255',
        'telephone' => 'required|string|max:20',
        'mode_paiement' => 'required|in:cash,mobile_money,carte',
        'instructions' => 'nullable|string|max:500'
    ]);
    
    // Calculer le total
    $montant_total = 0;
    $totalPoints = 0;
    
    foreach ($cart as $item) {
        $montant_total += $item['price'] * $item['quantity'];
        $totalPoints += ($item['points'] ?? 0) * $item['quantity'];
    }
    
    // Créer la commande
    $commande = auth()->user()->commandes()->create([
        'montant_total' => $montant_total,
        'points_gagnes' => $totalPoints,
        'status' => 'en_attente',
        'adresse_livraison' => $validated['adresse_livraison'],
        'telephone' => $validated['telephone'],
        'mode_paiement' => $validated['mode_paiement'],
        'instructions' => $validated['instructions'] ?? null
    ]);
    
    // Ajouter les items de la commande
    foreach ($cart as $item) {
        $commande->items()->create([
            'plat_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }
    
    // Ajouter les points de fidélité à l'utilisateur
    if ($totalPoints > 0) {
        auth()->user()->addPoints($totalPoints, 'Commande #' . $commande->id);
    }
    
    // Vider le panier
    session()->forget('cart');
    
    return response()->json([
        'success' => true,
        'message' => 'Commande passée avec succès ! Vous avez gagné ' . $totalPoints . ' points de fidélité.',
        'commande_id' => $commande->id
    ]);
}
}