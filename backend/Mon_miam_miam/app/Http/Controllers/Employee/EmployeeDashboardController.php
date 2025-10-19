<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

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