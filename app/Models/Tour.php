<?php

namespace App\Models;

use App\Support\Vimeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Tour extends Model
{
    public const NAV_SAFARI = 'safari';

    public const NAV_MOUNTAIN_SAFARI = 'mountain_safari';

    public const NAV_EXPLORE_AFRICA = 'explore_africa';

    public const NAV_BUCKETS = [
        self::NAV_SAFARI,
        self::NAV_MOUNTAIN_SAFARI,
        self::NAV_EXPLORE_AFRICA,
    ];

    public const COUNTRY_KENYA = 'kenya';

    public const COUNTRY_TANZANIA = 'tanzania';

    public const COUNTRY_UGANDA = 'uganda';

    public const HOMEPAGE_COUNTRIES = [
        self::COUNTRY_KENYA,
        self::COUNTRY_TANZANIA,
        self::COUNTRY_UGANDA,
    ];

    /** @var list<string> */
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
        'kilimanjaro-lemosho-route',
    ];

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'featured_media_type', 'featured_video_url',
        'price', 'duration_days',
        'is_active', 'is_featured', 'sort_order', 'country',
        'nav_bucket', 'nav_safari', 'nav_mountain_safari', 'nav_explore_africa',
        'mountain_id', 'destination_id',
        'meta_title', 'meta_description',
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
        ];
    }

    /**
     * @return array<string, bool>
     */
    public static function navFlagAttributesFromBucket(string $bucket): array
    {
        return match ($bucket) {
            self::NAV_MOUNTAIN_SAFARI => [
                'nav_safari' => false,
                'nav_mountain_safari' => true,
                'nav_explore_africa' => false,
            ],
            self::NAV_EXPLORE_AFRICA => [
                'nav_safari' => false,
                'nav_mountain_safari' => false,
                'nav_explore_africa' => true,
            ],
            default => [
                'nav_safari' => true,
                'nav_mountain_safari' => false,
                'nav_explore_africa' => false,
            ],
        };
    }

    public const HOMEPAGE_FEATURED_INITIAL = 4;

    public static function countryLabel(string $country): string
    {
        return match ($country) {
            self::COUNTRY_KENYA => __('Kenyan Safaris'),
            self::COUNTRY_TANZANIA => __('Tanzania'),
            self::COUNTRY_UGANDA => __('Uganda'),
            default => ucfirst($country),
        };
    }

    /**
     * @return array{eyebrow: string, title: string, subtitle: string, show_more: string, show_less: string, view_all: string}
     */
    public static function countryHeadingMeta(string $country): array
    {
        return match ($country) {
            self::COUNTRY_KENYA => [
                'eyebrow' => __('Kenya'),
                'title' => __('Kenyan Safaris'),
                'subtitle' => __('Savanna wildlife, Mount Kenya treks, and curated journeys across the Rift Valley.'),
                'show_more' => __('Show more from Kenya'),
                'show_less' => __('Show fewer'),
                'view_all' => __('View all Kenyan safaris'),
            ],
            self::COUNTRY_TANZANIA => [
                'eyebrow' => __('Tanzania'),
                'title' => __('Tanzania'),
                'subtitle' => __('Serengeti plains, Ngorongoro, Kilimanjaro ascents, and Indian Ocean extensions.'),
                'show_more' => __('Show more from Tanzania'),
                'show_less' => __('Show fewer'),
                'view_all' => __('View all Tanzania safaris'),
            ],
            self::COUNTRY_UGANDA => [
                'eyebrow' => __('Uganda'),
                'title' => __('Uganda'),
                'subtitle' => __('Gorilla forests, Nile adventures, and classic savanna in the Pearl of Africa.'),
                'show_more' => __('Show more from Uganda'),
                'show_less' => __('Show fewer'),
                'view_all' => __('View all Uganda safaris'),
            ],
            default => [
                'eyebrow' => ucfirst($country),
                'title' => ucfirst($country),
                'subtitle' => '',
                'show_more' => __('Show more'),
                'show_less' => __('Show fewer'),
                'view_all' => __('View all safaris'),
            ],
        };
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    public function homepageFeaturedSortKey(): array
    {
        $countryOrder = array_search($this->country, self::HOMEPAGE_COUNTRIES, true);
        if ($countryOrder === false) {
            $countryOrder = 99;
        }

        $typeOrder = $this->isMountainSafariForHomepage() ? 0 : 1;

        return [$countryOrder, $typeOrder, (int) ($this->sort_order ?? 0)];
    }

    public function isMountainSafariForHomepage(): bool
    {
        $bucket = (string) ($this->nav_bucket ?? self::NAV_SAFARI);

        if ($bucket === self::NAV_MOUNTAIN_SAFARI || $this->nav_mountain_safari) {
            return true;
        }

        if ($this->mountain_id !== null) {
            return true;
        }

        if ($bucket === self::NAV_EXPLORE_AFRICA) {
            $haystack = strtolower($this->title.' '.(string) ($this->description ?? '').' '.(string) $this->slug);

            return (bool) preg_match(
                '/mount\\s+kenya|kilimanjaro|mount\\s+meru|naromoru|sirimon|chogoria|kamweti|timau|burguret|\\btrek\\b|mountaineer|summit|peak\\s+circuit|technical\\s+climbing/i',
                $haystack
            );
        }

        return false;
    }

    public function homepageSafariTypeLabel(): string
    {
        return $this->isMountainSafariForHomepage()
            ? __('Mountain safari')
            : __('Wildlife safari');
    }

    public function safariExperiences(): BelongsToMany
    {
        return $this->belongsToMany(SafariExperience::class)->withTimestamps();
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
     * Infer country from title/slug/description (longest needle wins per country group).
     */
    public static function inferCountryFromText(string $title, string $slug = '', string $description = ''): ?string
    {
        $haystack = strtolower($title.' '.$slug.' '.$description);

        $rules = [
            self::COUNTRY_UGANDA => [
                'uganda', 'bwindi', 'murchison', 'queen elizabeth', 'gorilla', 'kibale', 'entebbe',
            ],
            self::COUNTRY_TANZANIA => [
                'tanzania', 'serengeti', 'ngorongoro', 'kilimanjaro', 'zanzibar', 'arusha', 'meru',
                'ruaha', 'selous', 'tarangire', 'manyara', 'uhuru',
            ],
            self::COUNTRY_KENYA => [
                'kenya', 'masai mara', 'maasai mara', 'amboseli', 'tsavo', 'nakuru', 'samburu',
                'mount kenya', 'naromoru', 'sirimon', 'chogoria', 'kamweti', 'timau', 'burguret',
                'thingira', 'tcv cultural', 'nairobi', 'laikipia',
            ],
        ];

        foreach ($rules as $code => $needles) {
            usort($needles, fn (string $a, string $b): int => strlen($b) <=> strlen($a));
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return $code;
                }
            }
        }

        return null;
    }

    /**
     * @return array<string, \Illuminate\Support\Collection<int, self>>
     */
    public static function featuredForHomepageByCountry(): array
    {
        $tours = self::query()
            ->active()
            ->featured()
            ->whereIn('country', self::HOMEPAGE_COUNTRIES)
            ->get()
            ->sortBy(fn (self $tour) => $tour->homepageFeaturedSortKey())
            ->values();

        $grouped = [];
        foreach (self::HOMEPAGE_COUNTRIES as $country) {
            $countryTours = $tours->where('country', $country)->values();
            if ($countryTours->isNotEmpty()) {
                $grouped[$country] = $countryTours;
            }
        }

        return $grouped;
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
