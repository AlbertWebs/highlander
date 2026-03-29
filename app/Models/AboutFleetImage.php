<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutFleetImage extends Model
{
    protected $table = 'about_fleet_images';

    protected $fillable = ['image', 'caption', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function url(): ?string
    {
        if (! filled($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }
}
