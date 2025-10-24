<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'min_amount',
        'code',
        'image_url',
        'start_date',
        'end_date',
        'max_uses',
        'current_uses',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Vérifie si la promotion est valide aujourd'hui
     */
    public function isValid()
    {
        $now = now();
        return $this->is_active 
            && $this->start_date <= $now 
            && $this->end_date >= $now
            && ($this->max_uses === null || $this->current_uses < $this->max_uses);
    }

    /**
     * Récupère le texte de réduction formaté
     */
    public function getDiscountText()
    {
        if ($this->type === 'percentage') {
            return "-{$this->value}%";
        } elseif ($this->type === 'fixed_amount') {
            return "-{$this->value} FCFA";
        } else {
            return "Livraison gratuite";
        }
    }
}