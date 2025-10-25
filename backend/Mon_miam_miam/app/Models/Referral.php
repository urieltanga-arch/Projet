<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
        protected $fillable = ['referrer_id', 'referred_id', 'points_earned',
        
        'sponsor_id',
        'referee_id',
        'restaurant_id',
        'referral_code',
        'status',
        'sponsor_points_earned',
        'referee_points_earned',
        'validated_at',
        'first_order_id',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }
    protected $casts = [
        'validated_at' => 'datetime',
    ];
public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function firstOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'first_order_id');
    }

    // Générer un code de parrainage unique
    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    // Valider le parrainage
    public function validate(int $sponsorPoints, int $refereePoints): void
    {
        $this->update([
            'status' => 'validated',
            'sponsor_points_earned' => $sponsorPoints,
            'referee_points_earned' => $refereePoints,
            'validated_at' => now(),
        ]);
    }

}
