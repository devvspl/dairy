<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return asset($this->path);
    }

    public function getFileSizeAttribute(): string
    {
        $full = public_path($this->path);
        if (!file_exists($full)) return '-';
        $bytes = filesize($full);
        return $bytes >= 1048576
            ? round($bytes / 1048576, 2) . ' MB'
            : round($bytes / 1024, 1) . ' KB';
    }
}
