<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class SafariExperience extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'image', 'duration', 'country', 'mountain_id', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'mountain_id' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(SafariExperienceImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class)->withTimestamps();
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    /**
     * Hero / OG image: uploaded file or a neutral savanna fallback.
     */
    public function cardImageUrl(): string
    {
        if (filled($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80';
    }
}
