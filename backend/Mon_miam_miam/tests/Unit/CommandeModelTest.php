<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Commande;
use App\Models\User;
use App\Models\CommandeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandeModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_commande_user_relationship(): void
    {
        $user = User::factory()->create();
        $commande = Commande::factory()->create(['user_id' => $user->id]);
        
        $this->assertEquals($user->id, $commande->user->id);
    }

    public function test_commande_items_relationship(): void
    {
        $commande = Commande::factory()->create();
        $commandeItem = CommandeItem::factory()->create(['commande_id' => $commande->id]);
        
        $this->assertTrue($commande->items->contains($commandeItem));
    }

    public function test_commande_scope_pending(): void
    {
        Commande::factory()->create(['statut' => 'en_attente']);
        Commande::factory()->create(['statut' => 'livree']);
        
        $pendingCommandes = Commande::pending()->get();
        
        $this->assertCount(1, $pendingCommandes);
        $this->assertEquals('en_attente', $pendingCommandes->first()->statut);
    }

    public function test_commande_scope_completed(): void
    {
        Commande::factory()->create(['statut' => 'terminee']);
        Commande::factory()->create(['statut' => 'en_attente']);
        
        $completedCommandes = Commande::completed()->get();
        
        $this->assertCount(1, $completedCommandes);
        $this->assertEquals('terminee', $completedCommandes->first()->statut);
    }

    public function test_commande_total_attribute(): void
    {
        $commande = Commande::factory()->create(['montant_total' => 25.50]);
        
        $this->assertEquals(25.50, $commande->getTotalAttribute());
    }

    public function test_commande_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $commandeData = [
            'user_id' => $user->id,
            'montant_total' => 30.00,
            'points_earned' => 15,
            'status' => 'en_attente',
            'notes' => 'Test notes'
        ];
        
        $commande = Commande::create($commandeData);
        
        $this->assertEquals($user->id, $commande->user_id);
        $this->assertEquals(30.00, $commande->montant_total);
        $this->assertEquals(15, $commande->points_earned);
        $this->assertEquals('en_attente', $commande->status);
        $this->assertEquals('Test notes', $commande->notes);
    }

    public function test_commande_casting(): void
    {
        $commande = Commande::factory()->create([
            'montant_total' => '25.50',
            'points_earned' => '10'
        ]);
        
        $this->assertIsFloat($commande->montant_total);
        $this->assertIsInt($commande->points_earned);
    }
}
