<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Plat;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_index_displays_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/panier');
        
        $response->assertOk();
        $response->assertViewIs('cart.index');
    }

    public function test_cart_index_redirects_guest_to_login(): void
    {
        $response = $this->get('/panier');
        
        $response->assertRedirect('/login');
    }

    public function test_can_add_item_to_cart(): void
    {
        $user = User::factory()->create();
        $plat = Plat::factory()->create(['is_available' => true]);
        
        $response = $this->actingAs($user)->post('/panier/add/' . $plat->id, [
            'quantity' => 2
        ]);
        
        $response->assertRedirect('/panier');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'plat_id' => $plat->id,
            'quantity' => 2
        ]);
    }

    public function test_cannot_add_unavailable_plat_to_cart(): void
    {
        $user = User::factory()->create();
        $plat = Plat::factory()->create(['is_available' => false]);
        
        $response = $this->actingAs($user)->post('/panier/add/' . $plat->id, [
            'quantity' => 1
        ]);
        
        $response->assertRedirect('/menu');
        $response->assertSessionHas('error');
        
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'plat_id' => $plat->id
        ]);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $plat = Plat::factory()->create();
        
        // Add item to cart first
        $this->actingAs($user)->post('/panier/add/' . $plat->id, ['quantity' => 1]);
        
        $response = $this->actingAs($user)->patch('/panier/update/' . $plat->id, [
            'quantity' => 3
        ]);
        
        $response->assertRedirect('/panier');
        
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'plat_id' => $plat->id,
            'quantity' => 3
        ]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();
        $plat = Plat::factory()->create();
        
        // Add item to cart first
        $this->actingAs($user)->post('/panier/add/' . $plat->id, ['quantity' => 1]);
        
        $response = $this->actingAs($user)->delete('/panier/remove/' . $plat->id);
        
        $response->assertRedirect('/panier');
        
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'plat_id' => $plat->id
        ]);
    }

    public function test_can_clear_cart(): void
    {
        $user = User::factory()->create();
        $plat1 = Plat::factory()->create();
        $plat2 = Plat::factory()->create();
        
        // Add items to cart
        $this->actingAs($user)->post('/panier/add/' . $plat1->id, ['quantity' => 1]);
        $this->actingAs($user)->post('/panier/add/' . $plat2->id, ['quantity' => 2]);
        
        $response = $this->actingAs($user)->post('/panier/clear');
        
        $response->assertRedirect('/panier');
        
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id
        ]);
    }

    public function test_can_checkout_cart(): void
    {
        $user = User::factory()->create();
        $plat = Plat::factory()->create(['price' => 15.00]);
        
        // Add item to cart
        $this->actingAs($user)->post('/panier/add/' . $plat->id, ['quantity' => 2]);
        
        $response = $this->actingAs($user)->post('/panier/checkout', [
            'adresse_livraison' => '123 Test Street',
            'telephone_contact' => '0123456789'
        ]);
        
        $response->assertRedirect('/historique');
        
        $this->assertDatabaseHas('commandes', [
            'user_id' => $user->id,
            'montant_total' => 30.00,
            'statut' => 'en_attente'
        ]);
    }
}
