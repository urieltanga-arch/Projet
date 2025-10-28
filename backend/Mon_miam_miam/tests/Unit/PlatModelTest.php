<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Plat;
use App\Models\Commande;
use App\Models\CommandeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlatModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_plat_scope_available(): void
    {
        Plat::factory()->create(['is_available' => true]);
        Plat::factory()->create(['is_available' => false]);
        
        $availablePlats = Plat::available()->get();
        
        $this->assertCount(1, $availablePlats);
        $this->assertTrue($availablePlats->first()->is_available);
    }

    public function test_plat_scope_disponible(): void
    {
        Plat::factory()->create(['is_available' => true]);
        Plat::factory()->create(['is_available' => false]);
        
        $disponiblePlats = Plat::disponible()->get();
        
        $this->assertCount(1, $disponiblePlats);
        $this->assertTrue($disponiblePlats->first()->is_available);
    }

    public function test_plat_scope_par_categorie(): void
    {
        Plat::factory()->create(['category' => 'plat']);
        Plat::factory()->create(['category' => 'boisson']);
        
        $plats = Plat::parCategorie('plat')->get();
        
        $this->assertCount(1, $plats);
        $this->assertEquals('plat', $plats->first()->category);
    }

    public function test_plat_commande_items_relationship(): void
    {
        $plat = Plat::factory()->create();
        $commande = Commande::factory()->create();
        $commandeItem = CommandeItem::factory()->create([
            'plat_id' => $plat->id,
            'commande_id' => $commande->id
        ]);
        
        $this->assertTrue($plat->commandeItems->contains($commandeItem));
    }

    public function test_plat_fillable_attributes(): void
    {
        $platData = [
            'name' => 'Test Plat',
            'description' => 'Test Description',
            'price' => 15.50,
            'category' => 'plat',
            'image_url' => 'test.jpg',
            'total_points' => 10
        ];
        
        $plat = Plat::create($platData);
        
        $this->assertEquals('Test Plat', $plat->name);
        $this->assertEquals('Test Description', $plat->description);
        $this->assertEquals(15.50, $plat->price);
        $this->assertEquals('plat', $plat->category);
        $this->assertEquals('test.jpg', $plat->image_url);
        $this->assertEquals(10, $plat->total_points);
    }

    public function test_plat_price_casting(): void
    {
        $plat = Plat::factory()->create(['price' => '15.50']);
        
        $this->assertIsFloat($plat->price);
        $this->assertEquals(15.50, $plat->price);
    }

    public function test_plat_is_available_casting(): void
    {
        $plat = Plat::factory()->create(['is_available' => 1]);
        
        $this->assertIsBool($plat->is_available);
        $this->assertTrue($plat->is_available);
    }
}
