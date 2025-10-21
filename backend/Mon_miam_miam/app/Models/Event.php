<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function events()
{
    return $this->belongsToMany(Event::class, 'event_participants')
                ->withPivot('status')
                ->withTimestamps();
}
}
