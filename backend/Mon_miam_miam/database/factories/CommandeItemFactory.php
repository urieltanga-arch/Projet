<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Plat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommandeItem>
 */
class CommandeItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commande_id' => \App\Models\Commande::factory(),
            'plat_id' => Plat::factory(),
            'quantite' => $this->faker->numberBetween(1, 5),
            'prix_unitaire' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
