<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletReconciliationLog extends Model
{
    protected $fillable = [
        'user_subscription_id',
        'performed_by',
        'fix_type',
        'before_balance',
        'after_balance',
        'difference',
        'expected_balance',
        'actual_balance',
        'meta',
        'status',
        'notes',
        'ip_address',
    ];

    protected $casts = [
        'before_balance'  => 'decimal:2',
        'after_balance'   => 'decimal:2',
        'difference'      => 'decimal:2',
        'expected_balance'=> 'decimal:2',
        'actual_balance'  => 'decimal:2',
        'meta'            => 'array',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public static function record(
        int    $subscriptionId,
        string $fixType,
        float  $beforeBalance,
        float  $afterBalance,
        float  $expectedBalance,
        float  $actualBalance,
        array  $meta = [],
        string $notes = '',
        string $status = 'success'
    ): self {
        return self::create([
            'user_subscription_id' => $subscriptionId,
            'performed_by'         => auth()->id(),
            'fix_type'             => $fixType,
            'before_balance'       => $beforeBalance,
            'after_balance'        => $afterBalance,
            'difference'           => round($afterBalance - $beforeBalance, 2),
            'expected_balance'     => $expectedBalance,
            'actual_balance'       => $actualBalance,
            'meta'                 => $meta ?: null,
            'status'               => $status,
            'notes'                => $notes ?: null,
            'ip_address'           => request()->ip(),
        ]);
    }
}
