<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'plat_id',
        'nom',
        'quantite',
        'prix_unitaire',
        'instructions'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2'
    ];

    /**
     * Relation avec la commande
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Relation avec le plat
     */
    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    /**
     * Calculer le sous-total
     */
    public function getSousTotal()
    {
        return $this->quantite * $this->prix_unitaire;
    }
}