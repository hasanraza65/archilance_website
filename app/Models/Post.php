<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'cover_image', 'image_alt',
        'post_category_id', 'user_id', 'read_minutes',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical', 'noindex',
        'published_at', 'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean', 'noindex' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Published *and* actually due — a future published_at stays hidden. */
    public function scopeLive(Builder $q): Builder
    {
        return $q->where('is_published', true)
                 ->where(fn ($x) => $x->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }
}
