<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSafetyPoint extends Model
{
    protected $table = 'about_safety_points';

    protected $fillable = ['point_text', 'sort_order', 'is_active'];

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
}
