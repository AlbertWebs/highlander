<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SafariExperience extends Model
{
    public const HOMEPAGE_FEATURED_INITIAL = 4;

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'duration',
        'country', 'mountain_id',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'mountain_id' => 'integer',
        ];
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Hero / OG image: uploaded file or a neutral savanna fallback.
     */
    public function cardImageUrl(): string
    {
        if (filled($this->image)) {
            $path = trim((string) $this->image);
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            if (str_starts_with($path, '/storage/')) {
                return asset($path);
            }

            return Storage::disk('public')->url($path);
        }

        return 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80';
    }

    public function homepageSafariTypeLabel(): string
    {
        return $this->isMountainSafariForHomepage()
            ? __('Mountain safari')
            : __('Wildlife safari');
    }

    public function isMountainSafariForHomepage(): bool
    {
        if ($this->mountain_id !== null) {
            return true;
        }

        $haystack = strtolower($this->title.' '.(string) ($this->description ?? '').' '.(string) $this->slug);

        return (bool) preg_match(
            '/mount\\s+kenya|kilimanjaro|mount\\s+meru|mountain|\\btrek\\b|mountaineer|summit|peak|climb|hike/i',
            $haystack
        );
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public function homepageFeaturedSortKey(): array
    {
        $countryOrder = array_search(
            strtolower((string) ($this->country ?? '')),
            Tour::HOMEPAGE_COUNTRIES,
            true
        );
        if ($countryOrder === false) {
            $countryOrder = 99;
        }

        $typeOrder = $this->isMountainSafariForHomepage() ? 0 : 1;

        return [$countryOrder, $typeOrder, (int) ($this->sort_order ?? 0)];
    }

    /**
     * @return array<string, \Illuminate\Support\Collection<int, self>>
     */
    public static function featuredForHomepageByCountry(): array
    {
        $safaris = self::query()
            ->active()
            ->whereIn('country', Tour::HOMEPAGE_COUNTRIES)
            ->get()
            ->sortBy(fn (self $safari) => $safari->homepageFeaturedSortKey())
            ->values();

        $grouped = [];
        foreach (Tour::HOMEPAGE_COUNTRIES as $country) {
            $countrySafaris = $safaris->filter(
                fn (self $s) => strtolower((string) $s->country) === $country
            )->values();
            if ($countrySafaris->isNotEmpty()) {
                $grouped[$country] = $countrySafaris;
            }
        }

        return $grouped;
    }

    public static function inferCountryFromText(string $title, string $slug = '', string $description = ''): ?string
    {
        return Tour::inferCountryFromText($title, $slug, $description);
    }
}
