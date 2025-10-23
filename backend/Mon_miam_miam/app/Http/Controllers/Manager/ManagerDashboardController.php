<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index(Request $request)
    {
         $period = $request -> get('periode','jour');

         // Définir la période
        $dateDebut = match($periode) {
            'semaine' => Carbon::now()->startOfWeek(),
            'mois' => Carbon::now()->startOfMonth(),
            'jour' => Carbon::now()->startOfDay(),
            default => Carbon::now()->startOfDay(),
        };
    }
   

    public function show()
    {

    }
}