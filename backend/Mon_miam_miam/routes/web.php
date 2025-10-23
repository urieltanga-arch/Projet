<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\CommandeController;
use App\Http\Controllers\Employee\CartController;
use App\Models\Commande;
use App\Http\Controllers\Employee\CommandeEmployeeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TopClientsController;
use App\Http\Controllers\Admin\AdminDashboardController;



Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/mes-points', function() {$user = auth()->user();
        $history = $user->loyaltyPoints()->latest()->paginate(10);
        return view('loyalty.simple', compact('user', 'history'));
    })->name('loyalty.simple');

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
    Route::get('/mes-points', function() {$user = auth()->user();
        $history = $user->loyaltyPoints()->latest()->paginate(10);
        return view('loyalty.simple', compact('user', 'history'));
    })->name('loyalty.simple');
    
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('/mes-points', function() {
        $user = auth()->user();
        $history = $user->loyaltyPoints()->latest()->paginate(10);
        $referrals = $user->referrals()->with('referred')->latest()->get();
        return view('loyalty.simple', compact('user', 'history', 'referrals'));
    })->name('loyalty.simple');
    
    // NOUVELLE ROUTE pour valider un code de parrainage
    Route::post('/valider-parrainage', function() {
        $code = request('referral_code');
        
        // Trouver le parrain
        $referrer = \App\Models\User::where('referral_code', $code)->first();
        
        if (!$referrer) {
            return back()->with('error', 'Code invalide');
        }
        
        if ($referrer->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas utiliser votre propre code');
        }
        
        if (auth()->user()->referred_by) {
            return back()->with('error', 'Vous avez déjà utilisé un code de parrainage');
        }
        
        // Créer le parrainage
        \App\Models\Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => auth()->id(),
            'points_earned' => 100
        ]);
        
        // Mettre à jour l'utilisateur
        auth()->user()->update(['referred_by' => $referrer->id]);
        
        // Donner 100 points au parrain
        $referrer->addPoints(100, 'Parrainage de ' . auth()->user()->name);
        
        // Donner 50 points au filleul
        auth()->user()->addPoints(50, 'Bonus de bienvenue - parrainage');
        
        return back()->with('success', 'Code validé ! Vous avez gagné 50 points et votre parrain 100 points !');
        
    })->name('referral.validate');


// Routes pour les Employés
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/commandes', [App\Http\Controllers\Employee\EmployeeController::class, 'commandes'])->name('commandes');
    Route::put('/commandes/{commande}/statut', [EmployeeController::class, 'updateStatut'])->name('commandes.updateStatut');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Gestion des commandes
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes');
    
    // Changer le statut d'une commande
    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'changerStatut'])->name('commandes.statut');
    
    // Annuler une commande
    Route::patch('/commandes/{id}/annuler', [CommandeController::class, 'annuler'])->name('commandes.annuler');
    
    // Statistiques des commandes
    Route::get('/commandes/statistiques', [CommandeController::class, 'statistiques'])->name('commandes.statistiques');
    
    // Imprimer un ticket
    Route::get('/commandes/{id}/ticket', [CommandeController::class, 'imprimerTicket'])->name('commandes.ticket');
    
});
// Routes pour les employés (protégées par authentification)
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    
    // Gestion des commandes
 Route::get('/commandes', [CommandeEmployeeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/refresh', [CommandeEmployeeController::class, 'refresh'])->name('commandes.refresh');
    Route::get('/commandes/{commande}', [CommandeEmployeeController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{commande}/status', [CommandeEmployeeController::class, 'updateStatus'])->name('commandes.updateStatus');
    Route::post('/commandes/{commande}/note', [CommandeEmployeeController::class, 'addNote'])->name('commandes.addNote');
    Route::patch('/commandes/{commande}/cancel', [CommandeEmployeeController::class, 'cancel'])->name('commandes.cancel');
    // Changer le statut d'une commande

    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'changerStatut'])->name('commandes.statut');
    
    // Annuler une commande
    Route::patch('/commandes/{id}/annuler', [CommandeController::class, 'annuler'])->name('commandes.annuler');
    
    // Statistiques des commandes
    Route::get('/commandes/statistiques', [CommandeController::class, 'statistiques'])->name('commandes.statistiques');
    
    // Imprimer un ticket
    Route::get('/commandes/{id}/ticket', [CommandeController::class, 'imprimerTicket'])->name('commandes.ticket');
    
    // Menu
    Route::get('/menu', function() {
        return view('employee.menu');
    })->name('menu');
    
    // Réclamations
    Route::get('/reclamations', function() {
        return view('employee.reclamations');
    })->name('reclamations');
    
    // Statistiques
    Route::get('/statistiques', function() {
        return view('employee.statistiques');
    })->name('statistiques');
    
    // Dashboard
    Route::get('/dashboard', function() {
        // Récupérer les statistiques pour le dashboard
        $periode = request('periode', 'semaine');
        
        $commandesEnAttente = \App\Models\Commande::where('status', 'en_attente')->count();
        $commandesAujourdhui = \App\Models\Commande::whereDate('created_at', today())->count();
        $reclamationsNonTraitees = 0; // À implémenter
        $revenuJour = \App\Models\Commande::whereDate('created_at', today())
            ->where('status', 'livree')
            ->sum('montant_total');
        
        // Statistiques pour le graphique
        $statistiques = [
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'commandes' => [5, 8, 12, 9, 15, 20, 18],
            'revenus' => [25000, 40000, 55000, 45000, 70000, 95000, 85000]
        ];
        
        // Activité récente
        $activiteRecente = \App\Models\Commande::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($commande) {
                $icones = [
                    'nouvelle' => '🆕',
                    'en_preparation' => '👨‍🍳',
                    'prete' => '✅',
                    'en_livraison' => '🚚',
                    'livree' => '📦',
                    'annulee' => '❌'
                ];
                
                $couleurs = [
                    'nouvelle' => 'yellow',
                    'en_preparation' => 'blue',
                    'prete' => 'green',
                    'en_livraison' => 'purple',
                    'livree' => 'gray',
                    'annulee' => 'red'
                ];
                
                $types = [
                    'nouvelle' => 'Nouvelle commande',
                    'en_preparation' => 'En préparation',
                    'prete' => 'Commande prête',
                    'en_livraison' => 'En livraison',
                    'livree' => 'Commande livrée',
                    'annulee' => 'Commande annulée'
                ];
                
                return [
                    'id' => $commande->id,
                    'numero' => $commande->numero,
                    'type' => $types[$commande->statut] ?? 'Commande',
                    'icone' => $icones[$commande->statut] ?? '📋',
                    'couleur' => $couleurs[$commande->statut] ?? 'gray',
                    'temps' => $commande->created_at->diffForHumans()
                ];
            });
        
        return view('employee.dashboard', compact(
            'periode',
            'commandesEnAttente',
            'commandesAujourdhui',
            'reclamationsNonTraitees',
            'revenuJour',
            'statistiques',
            'activiteRecente'
        ));
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [App\Http\Controllers\Employee\CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/add/{plat}', [App\Http\Controllers\Employee\CartController::class, 'add'])->name('cart.add');
    Route::delete('/panier/remove/{plat}', [App\Http\Controllers\Employee\CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/panier/update/{plat}', [App\Http\Controllers\Employee\CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/panier/clear', [App\Http\Controllers\Employee\CartController::class, 'clear'])->name('cart.clear');
    Route::post('/panier/checkout', [App\Http\Controllers\Employee\CartController::class, 'checkout'])->name('cart.checkout');
    


});


Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/commandes', [App\Http\Controllers\Employee\CommandeEmployeeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/refresh', [CommandeEmployeeController::class, 'refresh'])->name('commandes.refresh');
    Route::get('/commandes/{commande}', [CommandeEmployeeController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{commande}/status', [CommandeEmployeeController::class, 'updateStatus'])->name('commandes.updateStatus');
    Route::post('/commandes/{commande}/note', [CommandeEmployeeController::class, 'addNote'])->name('commandes.addNote');
    Route::patch('/commandes/{commande}/cancel', [CommandeEmployeeController::class, 'cancel'])->name('commandes.cancel');
     Route::get('/reclamations', [EmployeeController::class, 'reclamations'])->name('reclamations');
    Route::put('/reclamations/{reclamation}/statut', [EmployeeController::class, 'updateStatutReclamation'])->name('reclamations.updateStatut');
});
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/menu', [App\Http\Controllers\Employee\MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [App\Http\Controllers\Employee\MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [App\Http\Controllers\Employee\MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{plat}/edit', [App\Http\Controllers\Employee\MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{plat}', [App\Http\Controllers\Employee\MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{plat}', [App\Http\Controllers\Employee\MenuController::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{plat}/toggle', [App\Http\Controllers\Employee\MenuController::class, 'toggleAvailability'])->name('menu.toggle');
});
Route::get('/top-clients', [TopClientsController::class, 'index'])
    ->name('top-clients')
    ->middleware(['auth', 'verified']);

});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin (déjà existant)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // GESTION DES EMPLOYÉS (NOUVEAU)
    Route::get('/employees', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'index'])->name('employees.index');
    Route::post('/employees', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'store'])->name('employees.store');
    Route::put('/employees/{id}', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'update'])->name('employees.update');
    Route::post('/employees/{id}/toggle-status', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'toggleStatus'])->name('employees.toggleStatus');
    Route::delete('/employees/{id}', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'destroy'])->name('employees.destroy');
});

Route::get('/historique', [App\Http\Controllers\HistoriqueController::class, 'index'])->name('historique.index');
Route::post('/historique/{id}/signaler', [App\Http\Controllers\HistoriqueController::class, 'signalerProbleme'])->name('historique.signaler');
Route::post('/historique/{id}/confirmer-livraison', [App\Http\Controllers\HistoriqueController::class, 'confirmerLivraison'])->name('historique.confirmer');
// Routes Administrateur (uniquement pour admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});
require __DIR__.'/auth.php';
