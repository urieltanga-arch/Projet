<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiniJeuxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_minijeux_index_displays_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/minijeux');
        
        $response->assertOk();
        $response->assertViewIs('minijeux.index');
    }

    public function test_minijeux_index_redirects_guest_to_login(): void
    {
        $response = $this->get('/minijeux');
        
        $response->assertRedirect('/login');
    }

    public function test_user_can_spin_roue_fortune_with_enough_points(): void
    {
        $user = User::factory()->create(['total_points' => 100]);
        
        $response = $this->actingAs($user)->postJson('/minijeux/roue/spin');
        
        $response->assertOk();
        $response->assertJson(['success' => true]);
        
        // Check that points were deducted
        $user->refresh();
        $this->assertLessThanOrEqual(105, $user->total_points); // 90 + max gain
        $this->assertGreaterThanOrEqual(90, $user->total_points); // 90 + min gain
    }

    public function test_user_cannot_spin_roue_fortune_with_insufficient_points(): void
    {
        $user = User::factory()->create(['total_points' => 5]);
        $initialPoints = $user->total_points;
        
        $response = $this->actingAs($user)->postJson('/minijeux/roue/spin');
        
        $response->assertJson([
            'success' => false,
            'message' => 'Points insuffisants'
        ]);
        
        // Check that points were not changed
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'total_points' => $initialPoints
        ]);
    }

    public function test_user_can_start_quiz(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/minijeux/quiz/start');
        
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['questions']);
    }

    public function test_user_can_finish_quiz(): void
    {
        $user = User::factory()->create(['total_points' => 0]);
        
        $response = $this->actingAs($user)->postJson('/minijeux/quiz/finish', [
            'score' => 8,
            'total' => 10
        ]);
        
        $response->assertOk();
        $response->assertJson(['success' => true]);
        
        // Check that user got points based on score
        $user->refresh();
        $this->assertGreaterThan(0, $user->total_points);
    }

    public function test_user_can_participate_in_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['is_active' => true]);
        
        $response = $this->actingAs($user)->post('/events/' . $event->id . '/participate');
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('event_participants', [
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);
    }

    public function test_user_cannot_participate_in_inactive_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['is_active' => false]);
        
        $response = $this->actingAs($user)->post('/events/' . $event->id . '/participate');
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseMissing('event_participants', [
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);
    }

    public function test_user_can_get_points_via_api(): void
    {
        $user = User::factory()->create(['total_points' => 150]);
        
        $response = $this->actingAs($user)->getJson('/user/points');
        
        $response->assertOk();
        $response->assertJson(['points' => 150]);
    }
}
