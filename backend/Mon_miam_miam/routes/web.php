<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\CommandeController;
use App\Http\Controllers\Employee\CommandeEmployeeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TopClientsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StatistiquesController;
use App\Http\Controllers\Gerant\DashboardGerantController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\MiniJeuxController;

// ============================================
// ROUTES PUBLIQUES (sans authentification)
// ============================================
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// ============================================
// ROUTES AUTHENTIFIÉES (tous les utilisateurs connectés)
// ============================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard général
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    
    // Points de fidélité
    Route::get('/mes-points', function() {
        $user = auth()->user();
        $history = $user->loyaltyPoints()->latest()->paginate(10);
        $referrals = $user->referrals()->with('referred')->latest()->get();
        return view('loyalty.simple', compact('user', 'history', 'referrals'));
    })->name('loyalty.simple');
    
    // Validation du code de parrainage
    Route::post('/valider-parrainage', function() {
        $code = request('referral_code');
        
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
        
        \App\Models\Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => auth()->id(),
            'points_earned' => 10
        ]);
        
        auth()->user()->update(['referred_by' => $referrer->id]);
        $referrer->addPoints(10, 'Parrainage de ' . auth()->user()->name);
        auth()->user()->addPoints(5, 'Bonus de bienvenue - parrainage');
        
        return back()->with('success', 'Code validé ! Vous avez gagné 5 points et votre parrain 10 points !');
    })->name('referral.validate');
    
    // Top clients
    Route::get('/top-clients', [TopClientsController::class, 'index'])->name('top-clients');

    // Panier
    Route::get('/panier', [App\Http\Controllers\Employee\CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/add/{plat}', [App\Http\Controllers\Employee\CartController::class, 'add'])->name('cart.add');
    Route::delete('/panier/remove/{plat}', [App\Http\Controllers\Employee\CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/panier/update/{plat}', [App\Http\Controllers\Employee\CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/panier/clear', [App\Http\Controllers\Employee\CartController::class, 'clear'])->name('cart.clear');
    Route::post('/panier/checkout', [App\Http\Controllers\Employee\CartController::class, 'checkout'])->name('cart.checkout');
    
    // Historique des commandes
    Route::get('/historique', [App\Http\Controllers\HistoriqueController::class, 'index'])->name('historique.index');
    Route::post('/historique/{id}/signaler', [App\Http\Controllers\HistoriqueController::class, 'signalerProbleme'])->name('historique.signaler');
    Route::post('/historique/{id}/confirmer-livraison', [App\Http\Controllers\HistoriqueController::class, 'confirmerLivraison'])->name('historique.confirmer');
});

// ============================================
// ROUTES POUR LES JEUX (tous les utilisateurs authentifiés)
// ============================================
Route::middleware(['auth'])->group(function () {
    
    // Page Mini-Jeux
    Route::get('/minijeux', [MiniJeuxController::class, 'index'])->name('minijeux.index');
    
    // Roue de Fortune
    Route::post('/minijeux/roue/spin', [MiniJeuxController::class, 'spinRoue'])->name('minijeux.roue.spin');
    
    // Quiz Cuisine
    Route::post('/minijeux/quiz/finish', [MiniJeuxController::class, 'finishQuiz'])->name('minijeux.quiz.finish');
    
    // Événements
    Route::post('/events/{id}/participate', [MiniJeuxController::class, 'participateEvent'])->name('events.participate');
    
    // API Points
    Route::get('/user/points', [MiniJeuxController::class, 'getUserPoints'])->name('user.points');
});

// ============================================
// ROUTES STUDENTS
// ============================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
    
    Route::get('/mes-points', function() {
        $user = auth()->user();
        $history = $user->loyaltyPoints()->latest()->paginate(10);
        return view('loyalty.simple', compact('user', 'history'));
    })->name('loyalty.simple');
});

// ============================================
// ROUTES EMPLOYEES
// ============================================
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function() {
        $periode = request('periode', 'semaine');
        
        $commandesEnAttente = \App\Models\Commande::where('status', 'en_attente')->count();
        $commandesAujourdhui = \App\Models\Commande::whereDate('created_at', today())->count();
        $reclamationsNonTraitees = 0;
        $revenuJour = \App\Models\Commande::whereDate('created_at', today())
            ->where('status', 'livree')
            ->sum('montant_total');
        
        $statistiques = [
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'commandes' => [5, 8, 12, 9, 15, 20, 18],
            'revenus' => [25000, 40000, 55000, 45000, 70000, 95000, 85000]
        ];
        
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
    
    // Gestion des commandes
    Route::get('/commandes', [CommandeEmployeeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/refresh', [CommandeEmployeeController::class, 'refresh'])->name('commandes.refresh');
    Route::get('/commandes/{commande}', [CommandeEmployeeController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{commande}/status', [CommandeEmployeeController::class, 'updateStatus'])->name('commandes.updateStatus');
    Route::post('/commandes/{commande}/note', [CommandeEmployeeController::class, 'addNote'])->name('commandes.addNote');
    Route::patch('/commandes/{commande}/cancel', [CommandeEmployeeController::class, 'cancel'])->name('commandes.cancel');
    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'changerStatut'])->name('commandes.statut');
    Route::patch('/commandes/{id}/annuler', [CommandeController::class, 'annuler'])->name('commandes.annuler');
    Route::get('/commandes/statistiques', [CommandeController::class, 'statistiques'])->name('commandes.statistiques');
    Route::get('/commandes/{id}/ticket', [CommandeController::class, 'imprimerTicket'])->name('commandes.ticket');
    
    // Gestion du menu
    Route::get('/menu', [App\Http\Controllers\Employee\MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [App\Http\Controllers\Employee\MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [App\Http\Controllers\Employee\MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{plat}/edit', [App\Http\Controllers\Employee\MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{plat}', [App\Http\Controllers\Employee\MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{plat}', [App\Http\Controllers\Employee\MenuController::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{plat}/toggle', [App\Http\Controllers\Employee\MenuController::class, 'toggleAvailability'])->name('menu.toggle');
    
    // Réclamations
    Route::get('/reclamations', [EmployeeController::class, 'reclamations'])->name('reclamations');
    Route::put('/reclamations/{reclamation}/statut', [EmployeeController::class, 'updateStatutReclamation'])->name('reclamations.updateStatut');
    
    // Statistiques
    Route::get('/statistiques', [EmployeeController::class, 'statistiques'])->name('statistiques');
    Route::get('/statistiques/export', [EmployeeController::class, 'exportStatistiques'])->name('statistiques.export');
});

// ============================================
// ROUTES ADMIN (admin, manager, student)
// ============================================
Route::middleware(['auth', 'role:admin,manager,student'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des employés
    Route::get('/employees', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'index'])->name('employees.index');
    Route::post('/employees', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'store'])->name('employees.store');
    Route::put('/employees/{id}', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'update'])->name('employees.update');
    Route::post('/employees/{id}/toggle-status', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'toggleStatus'])->name('employees.toggleStatus');
    Route::delete('/employees/{id}', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'destroy'])->name('employees.destroy');
    
    // Réclamations
    Route::get('/reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/{id}', [ReclamationController::class, 'show'])->name('reclamations.show');
    Route::patch('/reclamations/{id}/status', [ReclamationController::class, 'updateStatus'])->name('reclamations.updateStatus');
    Route::delete('/reclamations/{id}', [ReclamationController::class, 'destroy'])->name('reclamations.destroy');
    
    // Promotions et événements
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/create', [PromotionController::class, 'createPromotion'])->name('promotions.create');
    Route::post('/promotions', [PromotionController::class, 'storePromotion'])->name('promotions.store');
    Route::get('/promotions/{promotion}/edit', [PromotionController::class, 'editPromotion'])->name('promotions.edit');
    Route::put('/promotions/{promotion}', [PromotionController::class, 'updatePromotion'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [PromotionController::class, 'destroyPromotion'])->name('promotions.destroy');
    
    Route::get('/events/create', [PromotionController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [PromotionController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [PromotionController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [PromotionController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [PromotionController::class, 'destroyEvent'])->name('events.destroy');
    
    // Statistiques
    Route::get('/statistiques', [StatistiquesController::class, 'index'])->name('statistiques');
});

// ============================================
// ROUTES GERANT (manager)
// ============================================
Route::middleware(['auth', 'role:manager'])->prefix('gerant')->name('gerant.')->group(function () {
    Route::get('/dashboard', [DashboardGerantController::class, 'index'])->name('dashboard');
});

// ============================================
// ROUTES D'AUTHENTIFICATION
// ============================================
require __DIR__.'/auth.php';