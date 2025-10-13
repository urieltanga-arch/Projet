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
        'name' => 'Admin User',
         'email' => 'admin@monmiammiam.com',
         'phone' => '+237612345678',
         'password' => Hash::make('Admin123'),
         'role' => 'admin',
         'referral_code' => 'ADMIN001',
     ]);

          User::create([
         'name' => 'John Doe',
         'email' => 'john@example.com',
         'phone' => '+237698765432',
         'password' => Hash::make('Password123'),
         'role' => 'student',
         'referral_code' => 'JOHN1234',
     ]);
       
    // Créer un gérant
        User::create([
            'name' => 'Gérant Restaurant',
            'email' => 'manager@monmiammiam.com',
            'phone' => '+237698765432',
            'password' => Hash::make('Manager123'),
            'role' => 'manager',
            'location' => 'Yaoundé, UCAC-ICAM',
            'referral_code' => 'MANAGER01',
            'is_active' => true,
            'loyalty_points' => 0,
        ]);

        // Créer un employé
        User::create([
            'name' => 'Employé Cuisine',
            'email' => 'employee@monmiammiam.com',
            'phone' => '+237677889900',
            'password' => Hash::make('Employee123'),
            'role' => 'employee',
            'location' => 'Yaoundé, UCAC-ICAM',
            'referral_code' => 'EMP00001',
            'is_active' => true,
            'loyalty_points' => 0,
        ]);

        // Créer quelques étudiants de test
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@test.com',
            'phone' => '+237655443322',
            'password' => Hash::make('Password123'),
            'role' => 'student',
            'location' => 'Résidence La Terrasse, Bâtiment A',
            'referral_code' => 'JEAN1234',
            'is_active' => true,
            'loyalty_points' => 50,
        ]);

        User::create([
            'name' => 'Marie Kouam',
            'email' => 'marie@test.com',
            'phone' => '+237644332211',
            'password' => Hash::make('Password123'),
            'role' => 'student',
            'location' => 'Résidence La Terrasse, Bâtiment B',
            'referral_code' => 'MARIE567',
            'is_active' => true,
            'loyalty_points' => 30,
        ]);

        User::create([
            'name' => 'Paul Nkongo',
            'email' => 'paul@test.com',
            'phone' => '+237633221100',
            'password' => Hash::make('Password123'),
            'role' => 'student',
            'location' => 'Résidence Universitaire, Chambre 205',
            'referral_code' => 'PAUL8910',
            'is_active' => true,
            'loyalty_points' => 75,
            'referred_by' => 4, // Parrainé par Jean
        ]);

        // Message de confirmation
        $this->command->info('✅ 6 utilisateurs créés avec succès !');
        $this->command->info('📧 Emails de test :');
        $this->command->line('   Admin: admin@monmiammiam.com / Admin123');
        $this->command->line('   Manager: manager@monmiammiam.com / Manager123');
        $this->command->line('   Employee: employee@monmiammiam.com / Employee123');
        $this->command->line('   Student: jean@test.com / Password123');
    }
}
