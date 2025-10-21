<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $total_points = $user->total_points;
        $plats = $request->plats();

        if (!$user->referral_code) {
            $user->referral_code = User::generateReferralCode($user->email);
            $user->save();
        }

        return view('dashboard', [
            'points' => $user->total_points ?? 0, // Retourne 0 si la colonne n'existe pas
            'referralCode' => $user->referral_code ?? strtoupper(explode('@', $user->email)[0]) . '2025',
            'plats' => $plats, 
        ]);

        // Récupérer tous les plats disponibles, triés par catégorie
        $plats = Plat::where('is_available', true)
            ->latest()
            ->limit(2)
            ->get();
        
        return view('dashboard', compact('plats'));
    }

}