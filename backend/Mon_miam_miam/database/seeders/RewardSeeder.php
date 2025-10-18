<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;
use Illuminate\Support\Facades\DB;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'Boisson Gratuite',
                'description' => 'Obtenez une boisson offerte au restaurant',
                'points_required' => 500,
                'type' => 'free_drink',
                'is_active' => true,
            ],
            [
                'name' => 'Plat Principal',
                'description' => 'Un plat principal de votre choix offert',
                'points_required' => 1500,
                'type' => 'main_dish',
                'is_active' => true,
            ],
            [
                'name' => 'Réduction 10%',
                'description' => 'Réduction de 10% sur votre prochaine commande',
                'points_required' => 1000,
                'type' => 'discount',
                'is_active' => true,
            ],
            [
                'name' => 'Réduction 20%',
                'description' => 'Réduction de 20% sur votre prochaine commande',
                'points_required' => 2000,
                'type' => 'discount',
                'is_active' => true,
            ],
        ];

         DB::table('events')->insert($events);
        
        $this->command->info('evenements créés avec succès!');

    }
}

