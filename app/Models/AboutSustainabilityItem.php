<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSustainabilityItem extends Model
{
    protected $table = 'about_sustainability_items';

    protected $fillable = ['title', 'description', 'icon', 'sort_order', 'is_active'];

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
