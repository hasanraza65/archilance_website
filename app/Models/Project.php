<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'summary', 'body', 'facts', 'gallery', 'deliverables',
        'category_label', 'categories', 'card_image',
        'card_width', 'card_height', 'full_image', 'image_alt', 'tags', 'span', 'featured',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical', 'noindex',
        'sort', 'is_published',
    ];

    protected $casts = [
        'tags' => 'array', 'facts' => 'array', 'gallery' => 'array', 'deliverables' => 'array',
        'featured' => 'boolean',
        'is_published' => 'boolean', 'noindex' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort')->orderBy('id');
    }

    public function categoryList(): array
    {
        return array_values(array_filter(explode(' ', (string) $this->categories)));
    }
}
