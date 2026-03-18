<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'transaction_id', 'amount', 'status',
        'payment_method', 'items', 'payment_response',
        'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'paid_at', 'coupon_code', 'discount_amount',
        'shiprocket_order_id', 'shiprocket_shipment_id', 'shiprocket_awb',
        'shiprocket_courier', 'shiprocket_status', 'shiprocket_assigned_at',
    ];

    protected $casts = [
        'amount'                  => 'decimal:2',
        'discount_amount'         => 'decimal:2',
        'items'                   => 'array',
        'payment_response'        => 'array',
        'paid_at'                 => 'datetime',
        'shiprocket_assigned_at'  => 'datetime',
    ];

    public function isShiprocketAssigned(): bool
    {
        return !empty($this->shiprocket_order_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOrderId(): string
    {
        return 'PROD' . now()->format('ymdHis') . rand(100, 999);
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isSuccess(): bool  { return $this->status === 'success'; }
    public function isFailed(): bool   { return $this->status === 'failed'; }
}
