<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ExportLog extends Model
{
    protected $fillable = [
        'type', 'filename', 'path', 'filter_status', 'row_count', 'generated_by',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getDownloadUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getFileSizeAttribute(): string
    {
        if (!Storage::disk('public')->exists($this->path)) return '-';
        $bytes = Storage::disk('public')->size($this->path);
        return $bytes >= 1048576
            ? round($bytes / 1048576, 2) . ' MB'
            : round($bytes / 1024, 1) . ' KB';
    }
}
