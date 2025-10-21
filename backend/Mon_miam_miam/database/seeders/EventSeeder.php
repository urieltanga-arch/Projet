<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'name' => 'Soirée Karaoké',
                'description' => 'Venez chanter et vous amuser avec nous !',
                'type' => 'karaoke',
                'event_date' => Carbon::now()->addDays(14)->setTime(20, 0),
                'max_participants' => 50,
                'current_participants' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Match de Foot',
                'description' => 'Regardons ensemble le grand match sur écran géant',
                'type' => 'football',
                'event_date' => Carbon::now()->addDays(7)->setTime(18, 0),
                'max_participants' => 100,
                'current_participants' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'Soirée Dégustation',
                'description' => 'Découvrez nos nouveaux plats en avant-première',
                'type' => 'other',
                'event_date' => Carbon::now()->addDays(21)->setTime(19, 0),
                'max_participants' => 30,
                'current_participants' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
            $this->command->info('evenement ajouté  avec succès!');
        }
    }
}

