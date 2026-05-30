<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Destination extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Image for public cards (e.g. homepage): uploaded file, or a stock fallback by slug when none is set.
     */
    public function cardImageUrl(): string
    {
        if (filled($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return self::fallbackCardImageUrlForSlug((string) ($this->slug ?? ''));
    }

    /**
     * Curated landscape photos when no admin upload exists (Unsplash — hotlink allowed).
     *
     * @see https://unsplash.com/license
     */
    public static function fallbackCardImageUrlForSlug(string $slug): string
    {
        $key = strtolower(str_replace('_', '-', trim($slug)));

        $map = [
            'masai-mara' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
            'maasai-mara' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
            'okavango' => 'https://images.unsplash.com/photo-1580476264223-c852ea3c2796?auto=format&fit=crop&w=1200&q=80',
            'drakensberg' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80',
            'zanzibar-coast' => 'https://images.unsplash.com/photo-1583395956016-866010f7c5f5?auto=format&fit=crop&w=1200&q=80',
            'zanzibar' => 'https://images.unsplash.com/photo-1583395956016-866010f7c5f5?auto=format&fit=crop&w=1200&q=80',
            'amboseli' => 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1200&q=80',
            'serengeti' => 'https://images.unsplash.com/photo-1552410260-0e030b951b28?auto=format&fit=crop&w=1200&q=80',
            'ngorongoro' => 'https://images.unsplash.com/photo-1578895101408-1a36b834405b?auto=format&fit=crop&w=1200&q=80',
            'mount-kenya' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1200&q=80',
            'samburu' => 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?auto=format&fit=crop&w=1200&q=80',
            'tsavo' => 'https://images.unsplash.com/photo-1504173010661-7f199d6dc718?auto=format&fit=crop&w=1200&q=80',
            'lake-nakuru' => 'https://images.unsplash.com/photo-1474511320723-8678d6e14bcb?auto=format&fit=crop&w=1200&q=80',
        ];

        return $map[$key] ?? 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=80';
    }
}
