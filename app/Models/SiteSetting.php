<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $row = static::query()->where('key', $key)->first();
        if (! $row || $row->value === null) {
            return $default;
        }
        $decoded = json_decode($row->value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $row->value;
    }

    public static function setValue(string $key, mixed $value): void
    {
        $stored = is_array($value) ? json_encode($value) : (string) $value;
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored]
        );
        Cache::forget('site_setting_'.$key);
    }

    public static function incrementVisitors(): void
    {
        $current = (int) static::getValue('total_visitors', 0);
        static::setValue('total_visitors', (string) ($current + 1));
    }

    /**
     * Public URL for a stored path on the public disk, or null if missing.
     */
    public static function publicUrl(mixed $storedPath): ?string
    {
        if (! is_string($storedPath) || $storedPath === '') {
            return null;
        }
        if (! Storage::disk('public')->exists($storedPath)) {
            return null;
        }

        return Storage::disk('public')->url($storedPath);
    }

    /**
     * Full URL for an external image link or a path on the public disk.
     */
    public static function resolvePublicAssetUrl(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return static::publicUrl($value);
    }

    public static function isExternalUrl(?string $value): bool
    {
        $value = trim((string) $value);

        return $value !== '' && filter_var($value, FILTER_VALIDATE_URL);
    }
}
