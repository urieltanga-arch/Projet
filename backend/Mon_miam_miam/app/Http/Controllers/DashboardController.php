<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;

class DashboardController extends Controller
{
     public function index(Request $request)
    {
        $user = $request->user();
        
        $plats =Plat::where('is_available', true)
        ->latest()
        ->limit(2)
        ->get();

        return view('dashboard', [
            'points' => $user->fidelity_points ?? 2450,
            'referralCode' => $user->referral_code ?? strtoupper(explode('@', $user->email)[0]) . '2025',
            'plats' =>$plats, 
        ]);
    }
}
