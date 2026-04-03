<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    protected $fillable = [
        'user_id', 'location_id', 'label', 'flat_no', 'address', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function location() { return $this->belongsTo(Location::class); }

    /** Make this address the default and unset all others for the user */
    public function makeDefault(): void
    {
        static::where('user_id', $this->user_id)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute(): string
    {
        return $this->flat_no ? $this->flat_no . ', ' . $this->address : $this->address;
    }
}
