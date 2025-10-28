<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlatAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_cannot_see_unavailable_plats(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        // Créer des plats disponibles et indisponibles
        $availablePlat = Plat::factory()->create(['is_available' => true]);
        $unavailablePlat = Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($student)->get('/menu');
        
        $response->assertOk();
        $plats = $response->viewData('plats');
        
        // Vérifier que seuls les plats disponibles sont retournés
        $this->assertCount(1, $plats);
        $this->assertEquals($availablePlat->id, $plats->first()->id);
        $this->assertTrue($plats->first()->is_available);
    }

    public function test_students_cannot_access_unavailable_plat_details(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $unavailablePlat = Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($student)->get('/menu/' . $unavailablePlat->id);
        
        $response->assertStatus(404);
    }

    public function test_students_cannot_add_unavailable_plat_to_cart(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $unavailablePlat = Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($student)->postJson('/panier/add/' . $unavailablePlat->id);
        
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ce plat n\'est plus disponible'
        ]);
    }

    public function test_employees_can_see_all_plats_in_management(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        
        // Créer des plats disponibles et indisponibles
        $availablePlat = Plat::factory()->create(['is_available' => true]);
        $unavailablePlat = Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($employee)->get('/employee/menu');
        
        $response->assertOk();
        $plats = $response->viewData('plats');
        
        // Les employés doivent voir tous les plats (disponibles et indisponibles)
        $this->assertCount(2, $plats);
    }

    public function test_employee_can_toggle_plat_availability(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $plat = Plat::factory()->create(['is_available' => true]);
        
        $response = $this->actingAs($employee)->patch('/employee/menu/' . $plat->id . '/toggle');
        
        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_available' => false
        ]);
        
        // Vérifier que le plat est maintenant indisponible
        $this->assertDatabaseHas('plats', [
            'id' => $plat->id,
            'is_available' => false
        ]);
    }

    public function test_after_toggle_unavailable_plat_not_visible_to_students(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $student = User::factory()->create(['role' => 'student']);
        $plat = Plat::factory()->create(['is_available' => true]);
        
        // L'employé rend le plat indisponible
        $this->actingAs($employee)->patch('/employee/menu/' . $plat->id . '/toggle');
        
        // L'étudiant ne doit plus voir ce plat
        $response = $this->actingAs($student)->get('/menu');
        
        $response->assertOk();
        $plats = $response->viewData('plats');
        
        $this->assertCount(0, $plats);
    }
}