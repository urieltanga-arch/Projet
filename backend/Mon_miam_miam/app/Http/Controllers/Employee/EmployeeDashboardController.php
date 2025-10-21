<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeDashboardController extends Controller
{
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