<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferralCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'total_referrals',
        'total_earnings',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_earnings' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referralCode) {
            if (empty($referralCode->code)) {
                $referralCode->code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usages()
    {
        return $this->hasMany(ReferralUsage::class);
    }

    public function completedUsages()
    {
        return $this->hasMany(ReferralUsage::class)->where('status', 'completed');
    }
}
