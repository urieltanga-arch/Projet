<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RoueFortuneTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_with_enough_points_can_spin_the_wheel(): void
    {
        // ARRANGE : Un utilisateur connecté avec 100 points
        $user = User::factory()->create(['total_points' => 100]);
        
        // ACT : L'utilisateur lance la roue
        $response = $this->actingAs($user)->postJson('/minijeux/roue/spin');

        // ASSERT
        $response->assertOk();
        $response->assertJson(['success' => true]);

        // On récupère l'utilisateur mis à jour depuis la BDD
        $user->refresh();

        // On vérifie que le coût (10) a été déduit et le gain ajouté.
        // Puisque le gain est aléatoire, on vérifie juste que le total a changé.
        // Ici, on sait que le nouveau total doit être 90 + le gain.
        $this->assertLessThanOrEqual(105, $user->total_points); // 90 (coût) + 15 (gain max)
        $this->assertGreaterThanOrEqual(90, $user->total_points); // 90 (coût) + 0 (gain min)
    }

    public function test_a_user_with_insufficient_points_cannot_spin_the_wheel(): void
    {
        // ARRANGE : Un utilisateur avec seulement 5 points
        $user = User::factory()->create(['total_points' => 5]);
        $initialPoints = $user->total_points;

        // ACT
        $response = $this->actingAs($user)->postJson('/minijeux/roue/spin');

        // ASSERT
        // La réponse devrait indiquer une erreur
        $response->assertJson([
            'success' => false,
            'message' => 'Points insuffisants',
        ]);

        // On vérifie que les points de l'utilisateur n'ont PAS changé en BDD
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'total_points' => $initialPoints,
        ]);
    }
}