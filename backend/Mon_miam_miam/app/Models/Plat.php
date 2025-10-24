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

    
}
