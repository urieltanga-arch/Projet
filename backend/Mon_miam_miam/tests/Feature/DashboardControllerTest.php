<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Plat;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste que la page du tableau de bord s'affiche correctement pour un utilisateur authentifié.
     *
     * @return void
     */
    public function test_dashboard_page_is_displayed_for_authenticated_user(): void
    {
        // ARRANGE : Préparer l'environnement
        $user = User::factory()->create(['total_points' => 150]);
        Plat::factory()->count(3)->create(['is_available' => true]);

        // ACT : Simuler l'action
        $response = $this->actingAs($user)->get('/dashboard');

        // ASSERT : Vérifier les résultats
        $response->assertOk(); // Statut 200
        $response->assertViewIs('dashboard'); // Vérifie que la bonne vue est retournée
        $response->assertViewHas('points', 150); // Vérifie que les points sont passés à la vue
        $response->assertViewHas('plats'); // Vérifie que la variable $plats existe
    }

    /**
     * Teste qu'un invité est redirigé vers la page de connexion.
     *
     * @return void
     */
    public function test_guest_is_redirected_to_login(): void
    {
        // ACT
        $response = $this->get('/dashboard');

        // ASSERT
        $response->assertRedirect('/login');
    }
}