<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'company', 'platform', 'rating', 'quote', 'avatar', 'sort', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }
}
