<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande',
        'user_id',
        'statut',
        'montant_total',
        'items',
        'adresse_livraison',
        'telephone_contact',
        'notes',
        'preparee_a',
        'livree_a',
    ];

    protected $casts = [
        'items' => 'array',
        'montant_total' => 'decimal:2',
        'preparee_a' => 'datetime',
        'livree_a' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }

    public static function genererNumeroCommande(): string
    {
        return 'CMD-' . now()->format('Ymd') . '-' . str_pad(self::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function getTempsEcouleAttribute(): string
    {
        $diff = now()->diffInMinutes($this->created_at);
        
        if ($diff < 60) {
            return "Il y a {$diff}minutes";
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return "Il y a {$hours}h";
        } else {
            $days = floor($diff / 1440);
            return "Il y a {$days}j";
        }
    }
}