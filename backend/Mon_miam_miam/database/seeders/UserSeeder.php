<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'roy senghor',
                'email' => 'roy.senghor@gmail.com',
                'password' => bcrypt('Qsdfghjklmù1')
            ],
            ];
            DB::table('users')->insert($users);
        
        $this->command->info('Utilisaterus créés avec succès!');

    }
}
