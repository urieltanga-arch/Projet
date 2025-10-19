<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [EmployeeDashboardController::class, 'orders'])->name('orders');
    Route::get('/statistics', [EmployeeDashboardController::class, 'statistics'])->name('statistics');
    Route::get('/complaints', [EmployeeDashboardController::class, 'complaints'])->name('complaints');
});

// Routes pour les Gérants
Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('dashboard');
});

// Routes pour les Admins
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
});

require __DIR__.'/auth.php';
