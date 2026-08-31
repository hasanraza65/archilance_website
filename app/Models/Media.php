<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = ['disk', 'path', 'name', 'mime', 'size', 'width', 'height', 'alt', 'user_id'];

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getReadableSizeAttribute(): string
    {
        $b = (int) $this->size;
        if ($b < 1024) return $b . ' B';
        if ($b < 1048576) return round($b / 1024) . ' KB';
        return round($b / 1048576, 1) . ' MB';
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime, 'image/');
    }
}
