<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'eyebrow', 'h1_lead', 'h1_gold', 'lede', 'hero_image', 'content',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical', 'noindex',
        'is_published',
    ];

    protected $casts = ['content' => 'array', 'is_published' => 'boolean', 'noindex' => 'boolean'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Convenience for pulling a nested value out of the free-form content blob. */
    public function block(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }

    /**
     * Editable string for this page. Falls back to the blueprint default so a
     * page that has never been saved still renders exactly as designed.
     */
    public function text(string $key, $default = null)
    {
        // The editor stores keys flat ("hero.title"), so try the literal key
        // before letting data_get treat the dots as a path.
        $content = $this->content ?? [];
        $value = $content[$key] ?? data_get($content, $key);

        if ($value === null || $value === '' || $value === []) {
            $value = \App\Support\PageBlueprint::defaults($this->slug)[$key] ?? $default;
        }

        return $value;
    }

    /** The field definitions the admin form renders for this page. */
    public function blueprint(): array
    {
        return \App\Support\PageBlueprint::for($this->slug);
    }
}
