<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionChangeLog extends Model
{
    protected $fillable = [
        'user_subscription_id', 'changed_by', 'change_type',
        'old_values', 'new_values', 'notes',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function subscription() { return $this->belongsTo(UserSubscription::class, 'user_subscription_id'); }
    public function changedBy()    { return $this->belongsTo(User::class, 'changed_by'); }

    /**
     * Quick helper to record a change.
     */
    public static function record(
        int    $subscriptionId,
        int    $userId,
        string $type,
        array  $oldValues = [],
        array  $newValues = [],
        string $notes = ''
    ): self {
        return static::create([
            'user_subscription_id' => $subscriptionId,
            'changed_by'           => $userId,
            'change_type'          => $type,
            'old_values'           => $oldValues ?: null,
            'new_values'           => $newValues ?: null,
            'notes'                => $notes ?: null,
        ]);
    }
}
