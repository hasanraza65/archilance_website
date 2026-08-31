<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'slug', 'name', 'short_name', 'icon', 'hero_image', 'hero_image_sm', 'card_image',
        'image_alt', 'h1_lead', 'h1_gold', 'lede', 'stats', 'intro', 'deliverables',
        'process', 'tools', 'faqs', 'related', 'portfolio_filter',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical', 'noindex',
        'sort', 'is_published',
    ];

    protected $casts = [
        'stats' => 'array', 'intro' => 'array', 'deliverables' => 'array',
        'process' => 'array', 'tools' => 'array', 'faqs' => 'array', 'related' => 'array',
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

    /** Sibling services referenced by slug in `related`. */
    public function relatedServices()
    {
        $slugs = $this->related ?? [];
        if (! $slugs) return collect();

        return static::published()->whereIn('slug', $slugs)->get()
            ->sortBy(fn ($s) => array_search($s->slug, $slugs))
            ->values();
    }

    public function projects()
    {
        return Project::published()->ordered()
            ->when($this->portfolio_filter, fn ($q) =>
                $q->whereRaw('FIND_IN_SET(?, REPLACE(categories, " ", ","))', [$this->portfolio_filter]))
            ->limit(3);
    }
}
