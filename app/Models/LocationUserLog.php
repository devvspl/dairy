<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationUserLog extends Model
{
    protected $fillable = [
        'location_id',
        'user_id',
        'assigned_by',
        'action',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the location associated with this log
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the user (delivery person) associated with this log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who made the assignment
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
