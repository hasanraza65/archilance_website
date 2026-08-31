<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'price', 'period', 'hours', 'rate_note', 'features',
        'cta_label', 'cta_url', 'featured', 'sort', 'is_published',
    ];

    protected $casts = ['features' => 'array', 'featured' => 'boolean', 'is_published' => 'boolean'];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
