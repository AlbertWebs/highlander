<?php

namespace App\Models;

use App\Support\Vimeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    /** Shown under main nav Safari dropdown (general wildlife / game experiences). */
    public const NAV_SAFARI = 'safari';

    /** Shown under main nav Mountains dropdown (treks, peaks, mountain-focused itineraries). */
    public const NAV_MOUNTAIN_SAFARI = 'mountain_safari';

    /** Shown under main nav Explore Africa dropdown (destinations, Mount Kenya hub, culture-led trips). */
    public const NAV_EXPLORE_AFRICA = 'explore_africa';

    /** @var list<string> */
    public const NAV_BUCKETS = [
        self::NAV_SAFARI,
        self::NAV_MOUNTAIN_SAFARI,
        self::NAV_EXPLORE_AFRICA,
    ];

    /**
     * Mount Kenya trekking itineraries (public slugs). Shown under Explore Africa and linked from the Mount Kenya destination hub.
     *
     * @var list<string>
     */
    public const MOUNT_KENYA_TREK_SLUGS = [
        '3-days-naromoru-naromoru-route',
        '4-days-sirimon-naromoru-route',
        '4-days-sirimon-sirimon-route',
        '5-days-burguret-route',
        '5-days-chogoria-chogoria-route',
        '5-days-sirimon-chogoria-route',
        '5-days-kamweti-route',
        '5-days-timau-route',
        '7-days-mount-kenya-peaks-circuit',
        '7-days-naromoru-route-technical-climbing-expedition',
    ];

    /**
     * Cultural / community experiences around Mount Kenya shown on the same destination hub.
     *
     * @var list<string>
     */
    public const MOUNT_KENYA_CULTURAL_HUB_SLUGS = [
        'tcv-cultural-and-farm-experience',
        'thingira-cultural-festival',
    ];

    /** @return list<string> */
    public static function mountKenyaDestinationHubSlugs(): array
    {
        return array_values(array_unique(array_merge(
            self::MOUNT_KENYA_TREK_SLUGS,
            self::MOUNT_KENYA_CULTURAL_HUB_SLUGS,
        )));
    }

    /**
     * Tours linked from the Mount Kilimanjaro mountain hub (/mountains/mount-kilimanjaro).
     *
     * @var list<string>
     */
    public const MOUNT_KILIMANJARO_MOUNTAIN_HUB_SLUGS = [
        'kilimanjaro-lemosho-route',
    ];

    /** @return list<string> */
    public static function mountKilimanjaroMountainHubSlugs(): array
    {
        return self::MOUNT_KILIMANJARO_MOUNTAIN_HUB_SLUGS;
    }

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'featured_media_type', 'featured_video_url',
        'price', 'duration_days',
        'is_active', 'is_featured', 'sort_order', 'nav_bucket',
        'nav_safari', 'nav_mountain_safari', 'nav_explore_africa',
        'mountain_id', 'destination_id', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'nav_safari' => 'boolean',
            'nav_mountain_safari' => 'boolean',
            'nav_explore_africa' => 'boolean',
            'mountain_id' => 'integer',
            'destination_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Tour $tour): void {
            if (! $tour->nav_safari && ! $tour->nav_mountain_safari && ! $tour->nav_explore_africa) {
                $tour->fillFlagsFromNavBucket((string) ($tour->attributes['nav_bucket'] ?? $tour->nav_bucket ?? self::NAV_SAFARI));
            }
            $tour->nav_bucket = $tour->canonicalNavBucketFromFlags();
        });
    }

    /**
     * Derive checkbox flags from a single legacy nav_bucket value (seeders / imports).
     */
    public function fillFlagsFromNavBucket(string $bucket): void
    {
        $bucket = match ($bucket) {
            self::NAV_SAFARI, self::NAV_MOUNTAIN_SAFARI, self::NAV_EXPLORE_AFRICA => $bucket,
            default => self::NAV_SAFARI,
        };
        $this->nav_safari = $bucket === self::NAV_SAFARI;
        $this->nav_mountain_safari = $bucket === self::NAV_MOUNTAIN_SAFARI;
        $this->nav_explore_africa = $bucket === self::NAV_EXPLORE_AFRICA;
    }

    /**
     * Single stored nav_bucket for backwards compatibility (priority: mountain, then explore, then safari).
     */
    public function canonicalNavBucketFromFlags(): string
    {
        if ($this->nav_mountain_safari) {
            return self::NAV_MOUNTAIN_SAFARI;
        }
        if ($this->nav_explore_africa) {
            return self::NAV_EXPLORE_AFRICA;
        }
        if ($this->nav_safari) {
            return self::NAV_SAFARI;
        }

        return self::NAV_SAFARI;
    }

    /**
     * @return array{nav_safari: bool, nav_mountain_safari: bool, nav_explore_africa: bool}
     */
    public static function navFlagAttributesFromBucket(string $bucket): array
    {
        return [
            'nav_safari' => $bucket === self::NAV_SAFARI,
            'nav_mountain_safari' => $bucket === self::NAV_MOUNTAIN_SAFARI,
            'nav_explore_africa' => $bucket === self::NAV_EXPLORE_AFRICA,
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** Day-by-day itinerary (admin-managed; ordered by day_number). */
    public function itineraryDays(): HasMany
    {
        return $this->hasMany(TourItineraryDay::class)->orderBy('day_number');
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForNavBucket(Builder $query, string $bucket): Builder
    {
        return match ($bucket) {
            self::NAV_SAFARI => $query->where('nav_safari', true),
            self::NAV_MOUNTAIN_SAFARI => $query->where('nav_mountain_safari', true),
            self::NAV_EXPLORE_AFRICA => $query->where('nav_explore_africa', true),
            default => $query->whereRaw('1 = 0'),
        };
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function imageUrl(): ?string
    {
        $image = trim((string) ($this->image ?? ''));

        if ($image === '') {
            return null;
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '//')) {
            return $image;
        }

        if (str_starts_with($image, '/storage/')) {
            return asset($image);
        }

        return Storage::disk('public')->url($image);
    }

    /** Featured experience card on the homepage: Vimeo embed when URL parses; otherwise direct file (e.g. MP4). */
    public function featuredCardVimeoId(): ?string
    {
        if (($this->featured_media_type ?? 'image') !== 'video' || empty($this->featured_video_url)) {
            return null;
        }

        return Vimeo::idFromUrl($this->featured_video_url);
    }

    public function featuredCardShowsVideo(): bool
    {
        return ($this->featured_media_type ?? 'image') === 'video'
            && filled($this->featured_video_url);
    }

    /**
     * Suggested values for the Plan My Safari form (keyword + price heuristics).
     *
     * @return array<string, mixed>
     */
    public function planPrefillSuggestions(): array
    {
        $haystack = strtolower($this->title.' '.(string) ($this->description ?? ''));

        $destinationKeywords = [
            'maasai mara' => 'Maasai Mara',
            'masai mara' => 'Maasai Mara',
            'amboseli' => 'Amboseli',
            'tsavo' => 'Tsavo',
            'lake nakuru' => 'Lake Nakuru',
            'nakuru' => 'Lake Nakuru',
            'samburu' => 'Samburu',
            'serengeti' => 'Serengeti',
            'ngorongoro' => 'Ngorongoro',
            'zanzibar' => 'Zanzibar',
            'mount kenya' => 'Mount Kenya',
        ];
        $keys = array_keys($destinationKeywords);
        usort($keys, fn (string $a, string $b): int => strlen($b) <=> strlen($a));
        $destinations = [];
        foreach ($keys as $needle) {
            if (str_contains($haystack, $needle)) {
                $destinations[] = $destinationKeywords[$needle];
            }
        }
        $destinations = array_values(array_unique($destinations));

        $experienceTypes = [];
        if (preg_match('/kilimanjaro|mount\\s+meru|drakensberg|\\btrek\\b|mountaineer|summit\\s+bid/i', $haystack)) {
            $experienceTypes[] = __('Mountain Climbing');
        }
        if (str_contains($haystack, 'beach') || str_contains($haystack, 'zanzibar') || str_contains($haystack, 'coast')) {
            $experienceTypes[] = __('Beach Holiday');
        }
        if (preg_match('/photo|photograph|camera\\s+lens/i', $haystack)) {
            $experienceTypes[] = __('Photography Safari');
        }
        if (count($experienceTypes) === 0) {
            $experienceTypes[] = __('Wildlife Safari');
        }
        if ($this->price !== null && (float) $this->price >= 4000) {
            $experienceTypes[] = __('Luxury Safari');
        }
        $experienceTypes = array_values(array_unique($experienceTypes));

        $budgetRange = match (true) {
            $this->price === null => null,
            (float) $this->price < 1000 => 'under_1000',
            (float) $this->price < 2500 => '1000_2500',
            (float) $this->price < 5000 => '2500_5000',
            default => '5000_plus',
        };

        $duration = max(3, (int) ($this->duration_days ?? 7));
        $arrival = Carbon::now()->addDays(56)->startOfDay();
        $departure = (clone $arrival)->addDays($duration);

        $lines = [__('Interested in this package: :title', ['title' => $this->title])];
        if (filled($this->description)) {
            $lines[] = trim((string) $this->description);
        }
        if ($this->price !== null) {
            $lines[] = __('Indicative price from the brochure: $:price USD', ['price' => number_format((float) $this->price, 0)]);
        }

        $priceFloat = (float) ($this->price ?? 0);

        return [
            'arrival_date' => $arrival->format('Y-m-d'),
            'departure_date' => $departure->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'destinations' => $destinations,
            'experience_types' => $experienceTypes,
            'budget_range' => $budgetRange,
            'special_requests' => implode("\n\n", array_filter($lines)),
            'transport_type' => '4x4_safari',
            'accommodation_type' => $priceFloat >= 3500 ? 'luxury_lodge' : ($priceFloat >= 1500 ? 'mid_lodge' : 'tented_camp'),
        ];
    }
}
