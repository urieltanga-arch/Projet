<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Plat;
use App\Models\Commande;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_page_displays_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $plats = Plat::factory()->count(5)->create(['is_available' => true]);
        
        $response = $this->actingAs($user)->get('/menu');
        
        $response->assertOk();
        $response->assertViewIs('menu');
        $response->assertViewHas('plats');
    }

    public function test_menu_page_redirects_guest_to_login(): void
    {
        $response = $this->get('/menu');
        
        $response->assertRedirect('/login');
    }

    public function test_menu_shows_only_available_plats(): void
    {
        $user = User::factory()->create();
        Plat::factory()->create(['is_available' => true]);
        Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($user)->get('/menu');
        
        $response->assertOk();
        $plats = $response->viewData('plats');
        $this->assertCount(1, $plats);
    }

    public function test_menu_can_filter_by_category(): void
    {
        $user = User::factory()->create();
        Plat::factory()->create(['category' => 'plat']);
        Plat::factory()->create(['category' => 'boisson']);
        
        $response = $this->actingAs($user)->get('/menu?category=plat');
        
        $response->assertOk();
        $plats = $response->viewData('plats');
        $this->assertCount(1, $plats);
        $this->assertEquals('plat', $plats->first()->category);
    }
}
