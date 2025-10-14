<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();


              // Log avant création
        \Log::info('Tentative de création utilisateur', ['email' => $validated['email']]);
            // Créer l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'location' => $validated['location'] ?? null,
                'role' => 'student',
            ]);
             \Log::info('Utilisateur créé avec succès', ['user_id' => $user->id]);

            // Gérer le parrainage si un code est fourni
            if (isset($validated['referral_code'])) {
                $referrer = User::where('referral_code', $validated['referral_code'])->first();
                
                if ($referrer) {
                    $user->referred_by = $referrer->id;
                    $user->save();

                    // Créer l'enregistrement de parrainage
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_id' => $user->id,
                        'reward_given' => false,
                    ]);
                }
            }

            // Créer un token d'authentification
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'loyalty_points' => $user->loyalty_points,
                        'referral_code' => $user->referral_code,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connexion d'un utilisateur
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        // Vérifier les identifiants
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Vérifier si le compte est actif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce compte est désactivé. Veuillez contacter l\'administration.',
            ], 403);
        }

        // Supprimer les anciens tokens
        $user->tokens()->delete();

        // Créer un nouveau token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'loyalty_points' => $user->loyalty_points,
                    'referral_code' => $user->referral_code,
                    'location' => $user->location,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 200);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ], 200);
    }

    /**
     * Récupérer les informations de l'utilisateur connecté
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'loyalty_points' => $user->loyalty_points,
                    'referral_code' => $user->referral_code,
                    'location' => $user->location,
                    'created_at' => $user->created_at,
                ]
            ]
        ], 200);
    }

    /**
     * Réinitialisation du mot de passe (à implémenter)
     */
    public function forgotPassword(Request $request)
    {
        // À implémenter avec un système d'email
        return response()->json([
            'success' => true,
            'message' => 'Un email de réinitialisation a été envoyé',
        ], 200);
    }
}
            