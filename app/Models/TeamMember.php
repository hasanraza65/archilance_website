<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'slug', 'name', 'role', 'headline', 'team', 'blurb', 'bio', 'quote', 'location',
        'extra', 'focus', 'expertise', 'highlights', 'education',
        'email', 'linkedin', 'behance',
        'photo', 'photo_full', 'image_alt',
        'parent_id', 'is_leadership', 'sort', 'is_published', 'has_profile',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'canonical', 'noindex',
    ];

    protected $casts = [
        'is_leadership' => 'boolean',
        'is_published' => 'boolean',
        'has_profile' => 'boolean',
        'noindex' => 'boolean',
        'focus' => 'array',
        'expertise' => 'array',
        'highlights' => 'array',
        'education' => 'array',
    ];

    public const TEAMS = [
        'lead' => 'Leadership Team',
        'exec' => 'Executives',
        'biz'  => 'Business Team',
        'dev'  => 'Software Development',
        'mgr'  => 'Managers',
        'team' => 'Team Members',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort')->orderBy('id');
    }

    /**
     * Eager-loaded subtree, so rendering the chart is a single query.
     * Published-only: unpublishing someone already drops them from the head
     * count, the sitemap and their profile page, so leaving them drawn on the
     * chart was the odd one out.
     */
    public function childrenTree()
    {
        return $this->children()->where('is_published', true)->with('childrenTree');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function teamLabel(): string
    {
        return self::TEAMS[$this->team] ?? 'Team Members';
    }

    /* ------------------------------------------------------------ profiles */

    /** Only these get a page and a link out of the org chart. */
    public function scopeWithProfile(Builder $q): Builder
    {
        return $q->where('is_published', true)->where('has_profile', true);
    }

    public function hasPublicProfile(): bool
    {
        return (bool) $this->is_published && (bool) $this->has_profile;
    }

    /** The larger portrait if one was uploaded, else the chart thumbnail. */
    public function portrait(): ?string
    {
        return $this->photo_full ?: $this->photo;
    }

    public function photoAlt(): string
    {
        return $this->image_alt ?: trim($this->name . ($this->role ? ' — ' . $this->role : ''));
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $parts = array_values(array_filter($parts, fn ($p) => $p !== ''));

        if (! $parts) {
            return '—';
        }

        $first = mb_substr($parts[0], 0, 1);
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

        return mb_strtoupper($first . $last);
    }

    /**
     * `extra` is a free-text note and does not always hold a qualification —
     * some records use it for a discipline or a second job title. Showing one
     * of those under an "Education" heading would be a claim the record never
     * made, so both the parser and the page gate on this.
     */
    public static function looksLikeEducation(string $text): bool
    {
        return (bool) preg_match(
            '/(\bb\.?\s?arch|\bm\.?\s?arch|\bb\.?sc|\bm\.?sc|\bb\.?a\b|\bm\.?a\b|bachelor|master|phd|doctorate|diploma|graduated|university|college|institute|school of)/i',
            $text
        );
    }

    /** The raw note, but only when it reads as a qualification. */
    public function educationNote(): ?string
    {
        $note = trim((string) $this->extra);

        return $note !== '' && static::looksLikeEducation($note) ? $note : null;
    }

    /**
     * People at the same point in the chart, used for the "more of the team"
     * strip. Falls back to the same colour group when someone sits at the top
     * of a branch and therefore has no siblings.
     */
    public function peers(int $limit = 4)
    {
        $q = static::withProfile()->whereKeyNot($this->getKey());

        $siblings = (clone $q)->where('parent_id', $this->parent_id)
            ->orderBy('sort')->orderBy('id')->limit($limit)->get();

        if ($siblings->count() >= $limit) {
            return $siblings;
        }

        return $siblings->concat(
            (clone $q)->where('team', $this->team)
                ->whereNotIn('id', $siblings->pluck('id')->all())
                ->orderBy('sort')->orderBy('id')
                ->limit($limit - $siblings->count())->get()
        );
    }
}
