<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom'=> 'test',
            'prenom'=> 'user',
            'email'=> 'test.user@gmail.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        //creer plusieurs utilisateurs avec factory
        User::factory(10)->create();
    }
}
