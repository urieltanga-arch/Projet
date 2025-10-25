<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyLevel extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'color',
        'points_required',
        'multiplier',
        'benefits',
        'is_active',
        'order',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_active' => 'boolean',
        'multiplier' => 'decimal:1',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Méthode pour obtenir les niveaux par défaut
    public static function getDefaultLevels(): array
    {
        return [
            [
                'name' => 'Bronze',
                'color' => '#CD7F32',
                'points_required' => 0,
                'multiplier' => 1.0,
                'benefits' => ['Points de base'],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Silver',
                'color' => '#C0C0C0',
                'points_required' => 1000,
                'multiplier' => 1.2,
                'benefits' => ['+20% de points'],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Gold',
                'color' => '#FFD700',
                'points_required' => 2500,
                'multiplier' => 1.5,
                'benefits' => ['+50% de points', 'Livraison gratuite'],
                'is_active' => true,
                'order' => 3,
            ],
        ];
    }
}
