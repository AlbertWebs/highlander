<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SafariExperienceImage extends Model
{
    protected $fillable = [
        'safari_experience_id',
        'image',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function safariExperience(): BelongsTo
    {
        return $this->belongsTo(SafariExperience::class);
    }

    public function imageUrl(): ?string
    {
        $value = trim((string) ($this->image ?? ''));
        if ($value === '') {
            return null;
        }

        return Storage::disk('public')->url($value);
    }
}
