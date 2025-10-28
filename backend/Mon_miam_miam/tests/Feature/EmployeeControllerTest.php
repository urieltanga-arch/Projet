<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Commande;
use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_dashboard_displays_for_employee(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        
        $response = $this->actingAs($employee)->get('/employee/dashboard');
        
        $response->assertOk();
        $response->assertViewIs('employee.dashboard');
    }

    public function test_employee_dashboard_shows_statistics(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        
        // Create some test data
        Commande::factory()->count(3)->create(['statut' => 'en_attente']);
        Commande::factory()->count(2)->create(['statut' => 'livree']);
        
        $response = $this->actingAs($employee)->get('/employee/dashboard');
        
        $response->assertOk();
        $response->assertViewHas('commandesEnAttente', 3);
        $response->assertViewHas('commandesAujourdhui');
        $response->assertViewHas('revenuJour');
    }

    public function test_employee_can_view_commandes(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        Commande::factory()->count(5)->create();
        
        $response = $this->actingAs($employee)->get('/employee/commandes');
        
        $response->assertOk();
        $response->assertViewIs('employee.commandes.index');
    }

    public function test_employee_can_update_commande_status(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $commande = Commande::factory()->create(['statut' => 'en_attente']);
        
        $response = $this->actingAs($employee)->patch('/employee/commandes/' . $commande->id . '/status', [
            'statut' => 'en_preparation'
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('commandes', [
            'id' => $commande->id,
            'statut' => 'en_preparation'
        ]);
    }

    public function test_employee_can_view_menu_management(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        Plat::factory()->count(3)->create();
        
        $response = $this->actingAs($employee)->get('/employee/menu');
        
        $response->assertOk();
        $response->assertViewIs('employee.menu.index');
    }

    public function test_employee_can_create_plat(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        
        $platData = [
            'name' => 'Test Plat',
            'description' => 'Test Description',
            'price' => 15.50,
            'category' => 'plat',
            'image_url' => 'test.jpg'
        ];
        
        $response = $this->actingAs($employee)->post('/employee/menu', $platData);
        
        $response->assertRedirect('/employee/menu');
        
        $this->assertDatabaseHas('plats', $platData);
    }

    public function test_employee_can_toggle_plat_availability(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $plat = Plat::factory()->create(['is_available' => true]);
        
        $response = $this->actingAs($employee)->patch('/employee/menu/' . $plat->id . '/toggle');
        
        $response->assertRedirect('/employee/menu');
        
        $this->assertDatabaseHas('plats', [
            'id' => $plat->id,
            'is_available' => false
        ]);
    }

    public function test_non_employee_cannot_access_employee_routes(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/employee/dashboard');
        
        $response->assertStatus(403);
    }
}
