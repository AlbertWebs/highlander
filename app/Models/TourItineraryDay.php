<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TourItineraryDay extends Model
{
    protected $fillable = [
        'tour_id',
        'day_number',
        'title',
        'body',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'day_number' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }
}
