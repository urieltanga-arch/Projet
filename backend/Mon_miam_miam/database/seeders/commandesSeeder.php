<?php
// database/seeders/CommandesSeeder.php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommandesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR');
        
        // S'assurer qu'il y a des utilisateurs
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory(10)->create();
        }

        $plats = [
            ['nom' => 'Eru + jus d\'ananas', 'prix' => 1500],
            ['nom' => 'Ndole + Bissap + Tiramisu', 'prix' => 3000],
            ['nom' => 'Poulet pané', 'prix' => 2500],
            ['nom' => 'Tiramisu + Jus d\'ananas', 'prix' => 2000],
            ['nom' => 'Poisson Braisé', 'prix' => 2500],
            ['nom' => 'Okok sucré', 'prix' => 1000],
            ['nom' => 'Eru + Bissap + Tiramisu', 'prix' => 3000],
            ['nom' => 'Eru + jus de goyave', 'prix' => 1500],
            ['nom' => 'Magne Wapt', 'prix' => 800],
            ['nom' => 'Koki + Plantain', 'prix' => 1200],
        ];

        $statuts = ['nouvelle', 'en_preparation', 'prete', 'en_livraison', 'livree', 'annulee'];
        $quartiers = [
            'Bastos',
            'Essos',
            'Mvan',
            'Odza',
            'Mimboman',
            'Ngousso',
            'Emana',
            'Tsinga',
            'Mokolo',
            'Madagascar'
        ];

        // Créer 50 commandes
        for ($i = 1; $i <= 50; $i++) {
            $statut = $faker->randomElement($statuts);
            $user = $users->random();
            
            // Créer la commande
            $commande = Commande::create([
                'user_id' => $user->id,
                'numero' => str_pad($i + 1245, 4, '0', STR_PAD_LEFT),
                'statut' => $statut,
                'montant_total' => 0, // Sera calculé après
                'adresse_livraison' => $faker->randomElement($quartiers) . ', ' . $faker->streetAddress,
                'telephone_contact' => $faker->phoneNumber,
                'instructions_speciales' => $faker->boolean(30) ? $faker->sentence() : null,
                'created_at' => $faker->dateTimeBetween('-7 days', 'now'),
            ]);

            // Définir les horodatages selon le statut
            $this->setTimestamps($commande, $statut);

            // Ajouter 1-4 items à la commande
            $nbItems = $faker->numberBetween(1, 4);
            $montantTotal = 0;

            for ($j = 0; $j < $nbItems; $j++) {
                $plat = $faker->randomElement($plats);
                $quantite = $faker->numberBetween(1, 3);
                $sousTotal = $plat['prix'] * $quantite;
                $montantTotal += $sousTotal;

                CommandeItem::create([
                    'commande_id' => $commande->id,
                    'nom' => $plat['nom'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $plat['prix'],
                    'instructions' => $faker->boolean(20) ? 'Sans piment' : null,
                ]);
            }

            // Mettre à jour le montant total
            $commande->update(['montant_total' => $montantTotal]);
        }

        $this->command->info('50 commandes créées avec succès !');
    }

    /**
     * Définir les horodatages selon le statut
     */
    private function setTimestamps($commande, $statut)
    {
        $baseTime = $commande->created_at;

        switch ($statut) {
            case 'en_preparation':
                $commande->preparation_debut = $baseTime->copy()->addMinutes(5);
                break;

            case 'prete':
                $commande->preparation_debut = $baseTime->copy()->addMinutes(5);
                $commande->prete_a = $baseTime->copy()->addMinutes(25);
                break;

            case 'en_livraison':
                $commande->preparation_debut = $baseTime->copy()->addMinutes(5);
                $commande->prete_a = $baseTime->copy()->addMinutes(25);
                $commande->livraison_debut = $baseTime->copy()->addMinutes(30);
                break;

            case 'livree':
                $commande->preparation_debut = $baseTime->copy()->addMinutes(5);
                $commande->prete_a = $baseTime->copy()->addMinutes(25);
                $commande->livraison_debut = $baseTime->copy()->addMinutes(30);
                $commande->livree_a = $baseTime->copy()->addMinutes(50);
                break;

            case 'annulee':
                $commande->annulee_a = $baseTime->copy()->addMinutes(10);
                $commande->raison_annulation = 'Client non joignable';
                break;
        }

        $commande->save();
    }
}
