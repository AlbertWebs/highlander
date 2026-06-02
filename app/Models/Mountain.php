<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mountain extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'elevation_m', 'image', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'elevation_m' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function safariExperiences(): HasMany
    {
        return $this->hasMany(SafariExperience::class);
    }

    /**
     * @return Collection<int, SafariExperience>
     */
    public function activeSafariExperiences(): Collection
    {
        return SafariExperience::activeForMountain($this);
    }

    /**
     * Mountains linked from the main header "Mountains" dropdown (peak hubs only).
     *
     * @return Collection<int, self>
     */
    public static function forMainMenu(): Collection
    {
        /** @var list<string> $slugs */
        $slugs = array_values(array_filter(config('navigation.mountains_main_menu_slugs', [])));
        if ($slugs === []) {
            return self::query()->active()->orderBy('name')->get();
        }

        $order = collect($slugs)->flip();

        return self::query()
            ->active()
            ->whereIn('slug', $slugs)
            ->get()
            ->sortBy(fn (self $m) => $order[$m->slug] ?? 999)
            ->values();
    }
}
