<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
