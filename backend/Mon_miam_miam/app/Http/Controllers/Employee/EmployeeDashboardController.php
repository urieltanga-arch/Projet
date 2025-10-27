<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API Mon Miam Miam",
 * description="Documentation de l'API pour l'application du restaurant ZeDuc@Space"
 * )
 */
class EmployeeDashboardController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/menu-du-jour",
     * summary="Récupère le menu du jour",
     * description="Retourne la liste des plats disponibles aujourd'hui.",
     * @OA\Response(
     * response=200,
     * description="Opération réussie",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="nom", type="string", example="Poulet DG"),
     * @OA\Property(property="prix", type="number", format="float", example=2500.00)
     * )
     * )
     * )
     * )
     */
    public function index()
    {
        return view('employee.dashboard');
    }

    public function orders()
    {
        // Logique pour les commandes
        return view('employee.orders');
    }

    public function statistics()
    {
        // Logique pour les statistiques
        return view('employee.statistics');
    }

    public function complaints()
    {
        // Logique pour les réclamations
        return view('employee.complaints');
    }
}