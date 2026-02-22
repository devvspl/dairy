<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipStep extends Model
{
    protected $fillable = [
        'step_number',
        'title',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
