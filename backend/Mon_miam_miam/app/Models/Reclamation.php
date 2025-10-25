<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'description',
        'reponse_employee',
        'type_probleme',
        'statut',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Scopes
     */
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

    /**
     * Accesseurs pour compatibilité avec l'ancienne version
     */
    public function getStatutDisplayAttribute()
    {
        return match($this->statut) {
            'non_traitee' => 'Total',
            'en_cours' => 'En attente',
            'resolue' => 'Traité',
            'fermee' => 'Fermée',
            default => $this->statut
        };
    }

    public function getTypeProblemAttribute()
    {
        return $this->type_probleme;
    }
}