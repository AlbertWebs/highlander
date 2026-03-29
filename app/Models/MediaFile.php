<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = [
        'path', 'original_name', 'mime', 'size', 'alt',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
