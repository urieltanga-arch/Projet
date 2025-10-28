<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoyaltySystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_loyalty_points(): void
    {
        $user = User::factory()->create(['total_points' => 150]);
        
        $response = $this->actingAs($user)->get('/mes-points');
        
        $response->assertOk();
        $response->assertViewIs('loyalty.simple');
        $response->assertViewHas('user');
        $response->assertViewHas('history');
    }

    public function test_user_can_validate_referral_code(): void
    {
        $referrer = User::factory()->create(['total_points' => 0]);
        $referred = User::factory()->create(['total_points' => 0, 'referred_by' => null]);
        
        $response = $this->actingAs($referred)->post('/valider-parrainage', [
            'referral_code' => $referrer->referral_code
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Check that both users got points
        $this->assertDatabaseHas('users', [
            'id' => $referrer->id,
            'total_points' => 10
        ]);
        
        $this->assertDatabaseHas('users', [
            'id' => $referred->id,
            'total_points' => 5,
            'referred_by' => $referrer->id
        ]);
        
        // Check referral record
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
            'points_earned' => 10
        ]);
    }

    public function test_user_cannot_use_invalid_referral_code(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/valider-parrainage', [
            'referral_code' => 'INVALID123'
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Code invalide');
    }

    public function test_user_cannot_use_own_referral_code(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/valider-parrainage', [
            'referral_code' => $user->referral_code
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Vous ne pouvez pas utiliser votre propre code');
    }

    public function test_user_cannot_use_referral_code_twice(): void
    {
        $referrer = User::factory()->create();
        $referred = User::factory()->create(['referred_by' => $referrer->id]);
        
        $response = $this->actingAs($referred)->post('/valider-parrainage', [
            'referral_code' => $referrer->referral_code
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Vous avez déjà utilisé un code de parrainage');
    }

    public function test_top_clients_page_displays(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/top-clients');
        
        $response->assertOk();
        $response->assertViewIs('top-clients');
    }

    public function test_loyalty_points_history_pagination(): void
    {
        $user = User::factory()->create();
        
        // Create multiple loyalty points
        for ($i = 0; $i < 15; $i++) {
            $user->addPoints(10, "Test point $i");
        }
        
        $response = $this->actingAs($user)->get('/mes-points');
        
        $response->assertOk();
        $history = $response->viewData('history');
        $this->assertCount(10, $history->items()); // Default pagination
    }
}
