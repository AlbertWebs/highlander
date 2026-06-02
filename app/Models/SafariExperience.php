<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
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

    protected static function booted(): void
    {
        static::saving(function (self $safari): void {
            if (filled($safari->country)) {
                $safari->country = strtolower((string) $safari->country);
            }
        });
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class)->withTimestamps();
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(SafariExperienceImage::class)->orderBy('sort_order');
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
            self::resolveHomepageCountry($this) ?? '',
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
     * Country code for homepage grouping (kenya / tanzania / uganda), or null if unknown.
     */
    public static function resolveHomepageCountry(self $safari): ?string
    {
        $stored = strtolower(trim((string) ($safari->country ?? '')));
        if (in_array($stored, Tour::HOMEPAGE_COUNTRIES, true)) {
            return $stored;
        }

        return self::inferCountryFromText(
            (string) $safari->title,
            (string) $safari->slug,
            (string) ($safari->description ?? '')
        );
    }

    /**
     * Active safari experiences from Admin → Safari for the homepage “Most Popular Tours” block.
     * Mountain styles first, then newest by date added.
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function popularForHomepage(int $limit = 8): \Illuminate\Support\Collection
    {
        return self::query()
            ->active()
            ->with(['mountain:id,name'])
            ->get()
            ->sortBy([
                fn (self $safari) => $safari->isMountainSafariForHomepage() ? 0 : 1,
                fn (self $safari) => -($safari->created_at?->getTimestamp() ?? 0),
                fn (self $safari) => -$safari->id,
            ])
            ->take($limit)
            ->values();
    }

    public function homepageCountryTag(): ?string
    {
        $country = self::resolveHomepageCountry($this);
        if ($country === null) {
            return null;
        }

        return Tour::countryHeadingMeta($country)['eyebrow'];
    }

    /**
     * Active safari experiences from Admin → Safari, grouped by country.
     *
     * @return array<string, \Illuminate\Support\Collection<int, self>>
     */
    public static function featuredForHomepageByCountry(): array
    {
        $safaris = self::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $grouped = [];
        foreach (Tour::HOMEPAGE_COUNTRIES as $country) {
            $countrySafaris = $safaris
                ->filter(fn (self $safari) => self::resolveHomepageCountry($safari) === $country)
                ->sortBy(fn (self $safari) => $safari->homepageFeaturedSortKey())
                ->values();

            if ($countrySafaris->isNotEmpty()) {
                $grouped[$country] = $countrySafaris;
            }
        }

        return $grouped;
    }

    /**
     * Active safari experiences for a country (Admin → Safari), sorted for display.
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function activeForCountry(string $country): \Illuminate\Support\Collection
    {
        if (! in_array($country, Tour::HOMEPAGE_COUNTRIES, true)) {
            return collect();
        }

        return self::query()
            ->active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->filter(fn (self $safari) => self::resolveHomepageCountry($safari) === $country)
            ->sortBy(fn (self $safari) => $safari->homepageFeaturedSortKey())
            ->values();
    }

    /**
     * Active safari styles explicitly linked to a mountain in Admin → Safari.
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function activeForMountain(Mountain $mountain): \Illuminate\Support\Collection
    {
        return self::query()
            ->active()
            ->where('mountain_id', $mountain->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    public static function paginateActiveForCountry(?string $country, int $perPage = 12): LengthAwarePaginator
    {
        $query = self::query()->active()->orderBy('sort_order')->orderByDesc('id');

        if ($country === null) {
            return $query->paginate($perPage)->withQueryString();
        }

        $filtered = $query->get()
            ->filter(fn (self $safari) => self::resolveHomepageCountry($safari) === $country)
            ->values();

        $page = max(1, (int) request()->query('page', 1));
        $items = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        return new Paginator(
            $items,
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public static function inferCountryFromText(string $title, string $slug = '', string $description = ''): ?string
    {
        return Tour::inferCountryFromText($title, $slug, $description);
    }
}
