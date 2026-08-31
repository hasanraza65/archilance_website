<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'label', 'hint', 'sort'];

    /** Whole settings table as a key => value map, cached until something saves. */
    public static function map(): array
    {
        return Cache::rememberForever('settings.map', fn () => static::pluck('value', 'key')->all());
    }

    public static function get(string $key, $default = null)
    {
        return static::map()[$key] ?? $default;
    }

    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.map');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.map'));
        static::deleted(fn () => Cache::forget('settings.map'));
    }
}
