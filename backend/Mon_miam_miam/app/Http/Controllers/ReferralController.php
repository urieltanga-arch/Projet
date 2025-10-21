<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Afficher la page de parrainage
     */
    public function index(Request $request)
    {
        $user = $request->user();

        try {
            // Générer ou récupérer le code de parrainage
            $referralCode = $user->referral_code ?? $this->generateReferralCode($user);

            // Si l'utilisateur n'a pas encore de code, on le sauvegarde
            if (!$user->referral_code) {
                $user->update(['referral_code' => $referralCode]);
            }

            // Récupérer les parrainages
            $referrals = Referral::with('referredUser')
                                ->where('referrer_id', $user->id)
                                ->latest()
                                ->get();

            return view('referral', [
                'referralCode' => $referralCode,
                'referrals' => $referrals,
                'totalReferrals' => $referrals->count(),
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement du programme de parrainage.');
        }
    }

    /**
     * Générer un code de parrainage unique
     */
    private function generateReferralCode(User $user): string
    {
        // Méthode 1 : Basée sur le nom/email
        $baseName = Str::upper(Str::ascii(explode('@', $user->email)[0]));
        $baseCode = $baseName . '2025';
        
        // Méthode 2 : Code aléatoire (alternative)
        // $baseCode = Str::upper(Str::random(8));

        $code = $baseCode;
        $counter = 1;

        // Vérifier l'unicité du code
        while (User::where('referral_code', $code)->exists()) {
            $code = $baseCode . $counter;
            $counter++;
            
            // Sécurité pour éviter une boucle infinie
            if ($counter > 100) {
                $code = Str::upper(Str::random(10));
                break;
            }
        }

        return $code;
    }

    /**
     * Traiter un nouveau parrainage
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        try {
            $user = $request->user();

            // Vérifier si l'email n'est pas déjà parrainé
            $existingReferral = Referral::where('referred_email', $request->email)->exists();
            
            if ($existingReferral) {
                return back()->with('error', 'Cet email a déjà été parrainé.');
            }

            // Vérifier que l'utilisateur ne se parraine pas lui-même
            if ($request->email === $user->email) {
                return back()->with('error', 'Vous ne pouvez pas vous parrainer vous-même.');
            }

            // Créer le parrainage
            Referral::create([
                'referrer_id' => $user->id,
                'referred_email' => $request->email,
                'status' => 'pending',
            ]);

            // TODO: Envoyer un email d'invitation ici

            return back()->with('success', 'Invitation de parrainage envoyée avec succès!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'invitation.');
        }
    }

    /**
     * Afficher les statistiques de parrainage
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total' => $user->referrals()->count(),
            'completed' => $user->referrals()->where('status', 'completed')->count(),
            'pending' => $user->referrals()->where('status', 'pending')->count(),
        ];

        return response()->json($stats);
    }
}