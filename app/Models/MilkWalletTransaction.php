<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkWalletTransaction extends Model
{
    protected $fillable = [
        'user_id', 'user_subscription_id', 'type', 'amount',
        'litres', 'balance_after', 'description', 'transaction_date',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'litres'           => 'decimal:3',
        'balance_after'    => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function subscription() { return $this->belongsTo(UserSubscription::class, 'user_subscription_id'); }

    public function isCredit(): bool { return $this->type === 'credit'; }
    public function isDebit(): bool  { return $this->type === 'debit'; }

    public function scopeForUser($query, $userId) { return $query->where('user_id', $userId); }
    public function scopeCredits($query)           { return $query->where('type', 'credit'); }
    public function scopeDebits($query)            { return $query->where('type', 'debit'); }
}
