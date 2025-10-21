<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer les utilisateurs
        $user1 = User::firstOrCreate(
            ['email' => 'jemina@gmail.cm'],
            [
                'name' => 'Jemina',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'telephone' => '237600000001',
                'referral_code' => Str::random(8),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'alice@example.com'],
            [
                'name' => 'Alice',
                'password' => Hash::make('password'),
                'role' => 'student',
                'telephone' => '237600000002',
                'referral_code' => Str::random(8),
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'bob@example.com'],
            [
                'name' => 'Bob',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'telephone' => '237600000003',
                'referral_code' => Str::random(8),
            ]
        );

        // CORRECTION : Stocker les utilisateurs dans un tableau
        $users = [$user1, $user2, $user3];

        // 2. Créer les produits
        $products = [
            Product::firstOrCreate(['name' => 'Café'], ['price' => 1500]),
            Product::firstOrCreate(['name' => 'Thé'], ['price' => 1000]),
            Product::firstOrCreate(['name' => 'Jus naturel'], ['price' => 2000]),
            Product::firstOrCreate(['name' => 'Okok'], ['price' => 2500]),
            Product::firstOrCreate(['name' => 'Sanga'], ['price' => 3000]),
        ];

        // 3. Créer des commandes pour chaque utilisateur
        // CORRECTION : Utiliser le tableau $users qui est maintenant défini
        foreach ($users as $user) {
            $nbOrders = rand(2, 4);
            
            for ($i = 0; $i < $nbOrders; $i++) {
                $this->createOrder($user, $products);
            }
        }

        $this->command->info('Données de test créées avec succès!');
    }

    private function createOrder($user, $products)
    {
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 0,
            'status' => 'completed',
            'created_at' => now()->subDays(rand(1, 30)),
        ]);

        $orderTotal = 0;
        $nbProducts = rand(1, 3);
        
        for ($j = 0; $j < $nbProducts; $j++) {
            $product = $products[array_rand($products)];
            $quantity = rand(1, 3);
            
            $order->products()->attach($product->id, [
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ]);
            
            $orderTotal += $product->price * $quantity;
        }

        $order->update(['total_amount' => $orderTotal]);
    }
}