<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Reclamation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReclamationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reclamations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Reclamation::factory()->count(3)->create();
        
        $response = $this->actingAs($admin)->get('/admin/reclamations');
        
        $response->assertOk();
        $response->assertViewIs('admin.reclamations.index');
    }

    public function test_admin_can_view_specific_reclamation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reclamation = Reclamation::factory()->create();
        
        $response = $this->actingAs($admin)->get('/admin/reclamations/' . $reclamation->id);
        
        $response->assertOk();
        $response->assertViewIs('admin.reclamations.show');
        $response->assertViewHas('reclamation');
    }

    public function test_admin_can_update_reclamation_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reclamation = Reclamation::factory()->create(['status' => 'en_attente']);
        
        $response = $this->actingAs($admin)->patch('/admin/reclamations/' . $reclamation->id . '/status', [
            'status' => 'traitee'
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('reclamations', [
            'id' => $reclamation->id,
            'status' => 'traitee'
        ]);
    }

    public function test_admin_can_delete_reclamation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reclamation = Reclamation::factory()->create();
        
        $response = $this->actingAs($admin)->delete('/admin/reclamations/' . $reclamation->id);
        
        $response->assertRedirect('/admin/reclamations');
        
        $this->assertDatabaseMissing('reclamations', [
            'id' => $reclamation->id
        ]);
    }

    public function test_employee_can_view_reclamations(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        Reclamation::factory()->count(2)->create();
        
        $response = $this->actingAs($employee)->get('/employee/reclamations');
        
        $response->assertOk();
        $response->assertViewIs('employee.reclamations');
    }

    public function test_employee_can_update_reclamation_status(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $reclamation = Reclamation::factory()->create(['status' => 'en_attente']);
        
        $response = $this->actingAs($employee)->put('/employee/reclamations/' . $reclamation->id . '/statut', [
            'status' => 'en_cours'
        ]);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('reclamations', [
            'id' => $reclamation->id,
            'status' => 'en_cours'
        ]);
    }

    public function test_user_can_create_reclamation(): void
    {
        $user = User::factory()->create();
        
        $reclamationData = [
            'sujet' => 'Test Reclamation',
            'description' => 'Test Description',
            'commande_id' => null
        ];
        
        $response = $this->actingAs($user)->post('/reclamations', $reclamationData);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('reclamations', [
            'user_id' => $user->id,
            'sujet' => 'Test Reclamation',
            'description' => 'Test Description',
            'status' => 'en_attente'
        ]);
    }

    public function test_non_admin_cannot_access_admin_reclamation_routes(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/admin/reclamations');
        
        $response->assertStatus(403);
    }
}
