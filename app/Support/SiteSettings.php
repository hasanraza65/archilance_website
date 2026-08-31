<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Thin read-only wrapper the views use as `$s`. Everything comes from one
 * cached query, so a page render never hits the settings table repeatedly.
 */
class SiteSettings
{
    protected array $map;

    public function __construct()
    {
        $this->map = Setting::map();
    }

    public function get(string $key, $default = null)
    {
        $v = $this->map[$key] ?? null;

        return ($v === null || $v === '') ? $default : $v;
    }

    public function has(string $key): bool
    {
        return ! empty($this->map[$key]);
    }

    /** "Archilance LLC" -> "Archilance" for the two-tone wordmark. */
    public function brandName(): string
    {
        $parts = explode(' ', (string) $this->get('site_name', 'Archilance LLC'));
        array_pop($parts);

        return $parts ? implode(' ', $parts) : (string) $this->get('site_name');
    }

    public function brandSuffix(): string
    {
        $parts = explode(' ', (string) $this->get('site_name', 'Archilance LLC'));

        return count($parts) > 1 ? end($parts) : '';
    }

    public function asset(string $key, ?string $fallback = null): ?string
    {
        $v = $this->get($key, $fallback);

        return $v ? (str_starts_with($v, 'http') ? $v : asset($v)) : null;
    }
}
