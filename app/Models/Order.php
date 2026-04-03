<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_type', 'user_subscription_id',
        'membership_plan_id', 'order_id', 'transaction_id',
        'amount', 'coupon_code', 'discount_amount',
        'status', 'payment_method', 'payment_response', 'wallet_meta', 'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'payment_response' => 'array',
        'wallet_meta'      => 'array',
        'paid_at'          => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function membershipPlan() { return $this->belongsTo(MembershipPlan::class); }
    public function subscription() { return $this->belongsTo(UserSubscription::class, 'user_subscription_id'); }

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isSuccess(): bool    { return $this->status === 'success'; }
    public function isFailed(): bool     { return $this->status === 'failed'; }
    public function isWalletTopup(): bool { return $this->order_type === 'wallet_topup'; }

    public static function generateOrderId(): string
    {
        return 'ORD' . time() . rand(1000, 9999);
    }
}
