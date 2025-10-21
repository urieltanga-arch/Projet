<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'points_earned',
        'status',
        'notes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'points_earned' => 'integer',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CommandeItem::class, 'commande_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'terminee');
    }
}