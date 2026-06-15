<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryHistory extends Model
{
    protected $table = 'delivery_history';
    
    protected $fillable = [
        'delivery_log_id',
        'action_type',
        'old_values',
        'new_values',
        'description',
        'changed_by',
        'changed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_at' => 'datetime',
    ];

    public function deliveryLog(): BelongsTo
    {
        return $this->belongsTo(DeliveryLog::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Record a delivery change
     */
    public static function record(
        int $deliveryLogId,
        string $actionType,
        array $oldValues = null,
        array $newValues = null,
        string $description = null,
        int $changedBy = null
    ): self {
        return self::create([
            'delivery_log_id' => $deliveryLogId,
            'action_type' => $actionType,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description ?? self::generateDescription($actionType, $oldValues, $newValues),
            'changed_by' => $changedBy ?? auth()->id(),
            'changed_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Auto-generate description based on action type and values
     */
    private static function generateDescription(string $actionType, $oldValues, $newValues): string
    {
        switch ($actionType) {
            case 'status_change':
                return "Status changed from '{$oldValues['status']}' to '{$newValues['status']}'";
            
            case 'quantity_change':
                return "Quantity changed from {$oldValues['quantity_delivered']}L to {$newValues['quantity_delivered']}L";
            
            case 'person_change':
                $oldPerson = $oldValues['marked_by_name'] ?? 'Unknown';
                $newPerson = $newValues['marked_by_name'] ?? 'Unknown';
                return "Delivery person changed from '{$oldPerson}' to '{$newPerson}'";
            
            case 'time_change':
                $oldTime = $oldValues['delivery_time'] ?? 'Not set';
                $newTime = $newValues['delivery_time'] ?? 'Not set';
                return "Delivery time changed from '{$oldTime}' to '{$newTime}'";
            
            case 'note_added':
                return "Note added: " . ($newValues['notes'] ?? '');
            
            case 'note_updated':
                return "Note updated";
            
            case 'bottle_status_change':
                $oldStatus = $oldValues['bottle_picked'] ? 'Picked' : 'Not picked';
                $newStatus = $newValues['bottle_picked'] ? 'Picked' : 'Not picked';
                return "Bottle status changed from '{$oldStatus}' to '{$newStatus}'";
            
            case 'delivery_created':
                return "Delivery entry created";
            
            case 'delivery_forwarded':
                $newDate = $newValues['delivery_date'] ?? 'Unknown date';
                return "Delivery forwarded to {$newDate}";
            
            default:
                return "Delivery updated";
        }
    }

    /**
     * Get formatted change summary
     */
    public function getFormattedChanges(): array
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue != $newValue) {
                    $changes[] = [
                        'field' => $key,
                        'old' => $oldValue,
                        'new' => $newValue,
                        'label' => $this->getFieldLabel($key)
                    ];
                }
            }
        }
        
        return $changes;
    }

    private function getFieldLabel(string $field): string
    {
        return match($field) {
            'status' => 'Status',
            'quantity_delivered' => 'Quantity',
            'delivery_time' => 'Time',
            'notes' => 'Notes',
            'bottle_picked' => 'Bottle Status',
            'marked_by' => 'Marked By',
            'marked_by_name' => 'Delivery Person',
            default => ucfirst(str_replace('_', ' ', $field))
        };
    }

    /**
     * Scope for specific action types
     */
    public function scopeActionType($query, string $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope for recent changes
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('changed_at', '>=', now()->subHours($hours));
    }
}