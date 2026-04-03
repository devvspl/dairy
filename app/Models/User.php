<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

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
        'phone',
        'profile_image',
        'user_type',
        'password',
        'otp',
        'otp_expires_at',
        'otp_verified_at',
        'mobile_verified_at',
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
            'otp_expires_at' => 'datetime',
            'otp_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'Admin';
    }

    /**
     * Check if user is a member
     *
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->user_type === 'Member';
    }

    /**
     * Check if user is a delivery person
     *
     * @return bool
     */
    public function isDeliveryPerson(): bool
    {
        return $this->user_type === 'Delivery Person';
    }

    /**
     * Get all subscriptions for this user
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get the active subscription for this user
     */
    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
                    ->active()
                    ->with('membershipPlan')
                    ->latest('start_date');
    }

    /**
     * Check if user has an active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Get the referral code for this user
     */
    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }

    /**
     * Get all loyalty points for this user
     */
    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    /**
     * Get available loyalty points balance
     */
    public function getLoyaltyPointsBalanceAttribute()
    {
        $earned = $this->loyaltyPoints()->where('type', 'earned')->sum('points');
        $redeemed = $this->loyaltyPoints()->where('type', 'redeemed')->sum('points');
        return $earned - $redeemed;
    }

    /**
     * Get all locations assigned to this delivery person
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_user')
                    ->withTimestamps();
    }

    /**
     * Get all location assignment logs for this user
     */
    public function locationLogs()
    {
        return $this->hasMany(LocationUserLog::class)
                    ->with(['location', 'assignedBy'])
                    ->latest();
    }

    /**
     * Get all orders for this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get saved delivery addresses
     */
    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class)->orderByDesc('is_default')->latest();
    }

    /**
     * Get default delivery address
     */
    public function defaultDeliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class)->where('is_default', true);
    }

    /**
     * Generate and store OTP for user
     */
    public function generateOtp()
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10), // OTP valid for 10 minutes
            'otp_verified_at' => null,
        ]);

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($otp)
    {
        if ($this->otp !== $otp) {
            return false;
        }

        if ($this->otp_expires_at && $this->otp_expires_at->isPast()) {
            return false;
        }

        $this->update([
            'otp_verified_at' => now(),
            'mobile_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return true;
    }

    /**
     * Check if OTP is valid
     */
    public function hasValidOtp()
    {
        return $this->otp && 
               $this->otp_expires_at && 
               $this->otp_expires_at->isFuture();
    }

    /**
     * Check if mobile is verified
     */
    public function isMobileVerified()
    {
        return !is_null($this->mobile_verified_at);
    }
}
