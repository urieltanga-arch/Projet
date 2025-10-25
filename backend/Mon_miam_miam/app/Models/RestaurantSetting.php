<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantSetting extends Model
{
    protected $fillable = [
        'restaurant_id',
        'opening_hours',
        'refund_delay_hours',
        'refund_fee_percentage',
        'auto_refund',
        'points_per_fcfa',
        'point_value_fcfa',
        'inscription_bonus_points',
        'first_order_bonus_points',
        'min_points_threshold',
        'max_points_percentage',
        'points_validity_months',
        'notification_before_expiry',
        'cumulative_with_promotion',
        'sponsor_points',
        'referee_points',
        'referee_bonus_first_order',
        'max_referrals_per_month',
        'attribution_delay_days',
        'min_first_order_amount',
        'require_confirmed_registration',
        'require_validated_first_order',
        'require_order_delivered',
        'no_refund_condition',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'auto_refund' => 'boolean',
        'notification_before_expiry' => 'boolean',
        'cumulative_with_promotion' => 'boolean',
        'require_confirmed_registration' => 'boolean',
        'require_validated_first_order' => 'boolean',
        'require_order_delivered' => 'boolean',
        'no_refund_condition' => 'boolean',
        'refund_fee_percentage' => 'decimal:2',
        'point_value_fcfa' => 'decimal:2',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Helper pour obtenir les heures d'un jour spécifique
    public function getOpeningHoursForDay(string $day): ?array
    {
        return $this->opening_hours[$day] ?? null;
    }

    // Méthode pour définir les heures par défaut
    public static function getDefaultOpeningHours(): array
    {
        return [
            'Lundi' => ['open' => '10:00', 'close' => '20:00'],
            'Mardi' => ['open' => '10:00', 'close' => '20:00'],
            'Mercredi' => ['open' => '10:00', 'close' => '20:00'],
            'Jeudi' => ['open' => '12:00', 'close' => '20:00'],
            'Vendredi' => ['open' => '10:00', 'close' => '20:00'],
            'Samedi' => ['open' => '10:00', 'close' => '20:00'],
            'Dimanche' => ['open' => '10:00', 'close' => '20:00'],
        ];
    }
}
