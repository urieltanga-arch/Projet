<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image_url',
        'total_points',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'toal_points' => 'integer',
    ];

    // Scope pour plats disponibles
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Relation avec les items de commande
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
/**
     * Relation avec les items de commande
     */
    public function commandeItems()
    {
        return $this->hasMany(CommandeItem::class);
    }

    /**
     * Obtenir le nombre total de ventes
     */
    public function getTotalVendusAttribute()
    {
        return $this->commandeItems()
            ->whereHas('commande', function($query) {
                $query->where('status', 'livree');
            })
            ->sum('quantite');
    }

    /**
     * Obtenir le revenu total généré
     */
    public function getRevenuTotalAttribute()
    {
        return $this->commandeItems()
            ->whereHas('commande', function($query) {
                $query->where('status', 'livree');
            })
            ->selectRaw('SUM(quantite * prix_unitaire) as total')
            ->value('total') ?? 0;
    }

    /**
     * Scope pour les plats disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope par catégorie
     */
    public function scopeParCategorie($query, $category)
    {
        return $query->where('category', $category);
    }
    
}
