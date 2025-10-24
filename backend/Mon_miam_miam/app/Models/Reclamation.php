<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'description',
        'type_probleme',
        'statut',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function scopeNonTraitees($query)
    {
        return $query->where('statut', 'non_traitee');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeResolues($query)
    {
        return $query->where('statut', 'resolue');
    }

    public function getNumeroReclamationAttribute(): string
    {
        return 'REC' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getTempsEcouleAttribute(): string
    {
        $diff = now()->diffInMinutes($this->created_at);
        
        if ($diff < 60) {
            return "Il y a {$diff}minutes";
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return "Il y a {$hours}heures";
        } else {
            $days = floor($diff / 1440);
            return "Il y a {$days}jour" . ($days > 1 ? 's' : '');
        }
    }
    public function reclamationsNonTraitees()
    {
    return $this->montant_total;
    }
}