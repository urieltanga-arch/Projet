<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plat;
use App\Models\Commande;
use App\Models\LoyaltyPoint;
use App\Models\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_points(): void
    {
        $user = User::factory()->create(['total_points' => 0]);
        
        $user->addPoints(50, 'Test points');
        
        $this->assertEquals(50, $user->fresh()->total_points);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'points' => 50,
            'description' => 'Test points',
            'type' => 'earned'
        ]);
    }

    public function test_user_can_use_points(): void
    {
        $user = User::factory()->create(['total_points' => 100]);
        
        $result = $user->usePoints(30, 'Test usage');
        
        $this->assertTrue($result);
        $this->assertEquals(70, $user->fresh()->total_points);
        $this->assertDatabaseHas('loyalty_points', [
            'user_id' => $user->id,
            'points' => -30,
            'description' => 'Test usage',
            'type' => 'spent'
        ]);
    }

    public function test_user_cannot_use_more_points_than_available(): void
    {
        $user = User::factory()->create(['total_points' => 50]);
        
        $result = $user->usePoints(100, 'Test usage');
        
        $this->assertFalse($result);
        $this->assertEquals(50, $user->fresh()->total_points);
    }

    public function test_user_role_methods(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $employee = User::factory()->create(['role' => 'employee']);
        $manager = User::factory()->create(['role' => 'manager']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($student->isStudent());
        $this->assertTrue($employee->isEmployee());
        $this->assertTrue($manager->isManager());
        $this->assertTrue($admin->isAdmin());

        $this->assertFalse($student->isEmployee());
        $this->assertFalse($employee->isStudent());
    }

    public function test_user_has_role_methods(): void
    {
        $user = User::factory()->create(['role' => 'employee']);

        $this->assertTrue($user->hasRole('employee'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertTrue($user->hasAnyRole(['employee', 'admin']));
        $this->assertFalse($user->hasAnyRole(['student', 'manager']));
    }

    public function test_user_generates_referral_code_on_creation(): void
    {
        $user = User::factory()->create();
        
        $this->assertNotNull($user->referral_code);
        $this->assertEquals(8, strlen($user->referral_code));
    }

    public function test_user_relationships(): void
    {
        $user = User::factory()->create();
        
        // Test loyalty points relationship
        $loyaltyPoint = LoyaltyPoint::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->loyaltyPoints->contains($loyaltyPoint));

        // Test referrals relationship
        $referral = Referral::factory()->create(['referrer_id' => $user->id]);
        $this->assertTrue($user->referrals->contains($referral));
    }
}
