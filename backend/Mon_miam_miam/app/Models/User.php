<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\LoyaltyPoint;
use App\Models\Referral;
use App\Models\Event;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'referred_by',
        'role'   ,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isStudent()
{
    return $this->role === 'student';
}

public function isEmployee()
{
    return $this->role === 'employee';
}

public function isManager()
{
    return $this->role === 'manager';
}

public function isAdmin()
{
    return $this->role === 'admin';
}

public function hasRole($role)
{
    return $this->role === $role;
}

public function hasAnyRole(array $roles)
{
    return in_array($this->role, $roles);
}

     // Relation avec les points
    public function loyaltyPoints()
    {
        return $this->hasMany(\App\Models\LoyaltyPoint::class);
    }

    // Générer automatiquement un code de parrainage
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
            }
        });
    }

    // Ajouter des points
    public function addPoints(int $points, string $description)
    {
        $this->loyaltyPoints()->create([
            'points' => $points,
            'description' => $description,
            'type' => 'earned'
        ]);
        $this->increment('total_points', $points);
    }

    // Utiliser des points
    public function usePoints(int $points, string $description)
    {
        if ($this->total_points >= $points) {
            $this->loyaltyPoints()->create([
                'points' => -$points,
                'description' => $description,
                'type' => 'spent'
            ]);
            $this->decrement('total_points', $points);
            return true;
        }
        return false;
    }
    // Relation avec les parrainages
    public function referrals()
    {
        return $this->hasMany(\App\Models\Referral::class, 'referrer_id');
    }

    // Compter les filleuls actifs
    public function getActiveReferralsCountAttribute()
    {
        return $this->referrals()->whereNotNull('referred_id')->count();
    }
}
