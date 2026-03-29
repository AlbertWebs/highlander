<?php

namespace App\Models;

use App\Support\Vimeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Tour extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'image', 'featured_media_type', 'featured_video_url',
        'price', 'duration_days',
        'is_active', 'is_featured', 'sort_order', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
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
