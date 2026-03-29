<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutPageSetting extends Model
{
    protected $fillable = [
        'hero_title', 'hero_subtitle', 'hero_image',
        'intro_heading', 'intro_paragraph_1', 'intro_paragraph_2', 'intro_image', 'intro_cta_label',
        'fleet_heading', 'fleet_body',
        'team_heading', 'team_body', 'team_image',
        'safety_heading', 'safety_body', 'safety_image',
        'core_values_section_title', 'sustainability_section_title', 'testimonials_section_title',
        'cta_heading', 'cta_body', 'cta_button_label',
    ];

    public static function content(): self
    {
        return static::query()->firstOrFail();
    }

    public function imageUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
