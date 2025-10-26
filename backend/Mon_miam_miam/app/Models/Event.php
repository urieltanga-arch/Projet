<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable =[
        'name',
        'description',
        'type',
        'event_date',
        'max_participants',
        'current_participants',
        'participants',
        'is_active'
    ];
     protected $casts = [
        'event_date' => 'datetime',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Obtenir les participants de l'événement
     */
    public function getParticipantsAttribute($value)
    {
        return collect(json_decode($value ?? '[]', true));
    }

    /**
     * Définir les participants de l'événement
     */
    public function setParticipantsAttribute($value)
    {
        $this->attributes['participants'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function isFull()
    {
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Vérifier si un utilisateur participe
     */
    public function hasParticipant($userId)
    {
        return $this->getParticipantsAttribute($this->attributes['participants'] ?? '[]')->contains($userId);
    }

    /**
     * Scope pour les événements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les événements à venir
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    /**
     * Scope pour les événements passés
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }
    public function events()
{
    return $this->belongsToMany(Event::class, 'event_participants')
                ->withPivot('status')
                ->withTimestamps();
}
}
