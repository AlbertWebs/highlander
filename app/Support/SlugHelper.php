<?php

namespace App\Support;

use Illuminate\Support\Str;

class SlugHelper
{
    public static function unique(string $modelClass, string $column, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while ($modelClass::query()
            ->where($column, $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
