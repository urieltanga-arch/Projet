<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
     public function index(Request $request)
    {
        $user = $request->user();
        
        return view('dashboard', [
            'points' => $user->fidelity_points ?? 2450,
            'referralCode' => $user->referral_code ?? strtoupper(explode('@', $user->email)[0]) . '2025',
            'orders' => [], // Vous pourrez ajouter vos vraies commandes plus tard
        ]);
    }
}
