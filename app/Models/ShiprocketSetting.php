<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiprocketSetting extends Model
{
    protected $table = 'shiprocket_settings';

    protected $fillable = [
        'enabled',
        'email',
        'password',
        'pickup_location',
        'default_city',
        'default_state',
        'default_pincode',
        'pkg_length',
        'pkg_breadth',
        'pkg_height',
        'pkg_weight',
    ];

    protected $casts = [
        'enabled'     => 'boolean',
        'pkg_length'  => 'float',
        'pkg_breadth' => 'float',
        'pkg_height'  => 'float',
        'pkg_weight'  => 'float',
    ];

    /**
     * Always returns the single settings row, creating defaults if missing.
     */
    public static function instance(): static
    {
        return static::firstOrCreate([], [
            'enabled'          => false,
            'pickup_location'  => 'Primary',
            'pkg_length'       => 10,
            'pkg_breadth'      => 10,
            'pkg_height'       => 10,
            'pkg_weight'       => 0.5,
        ]);
    }
}
