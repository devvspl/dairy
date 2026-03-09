<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralUsage extends Model
{
    protected $fillable = [
        'referral_code_id',
        'referred_user_id',
        'referrer_reward',
        'referee_reward',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'referrer_reward' => 'decimal:2',
        'referee_reward' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
