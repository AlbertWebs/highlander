<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_metas';

    protected $fillable = [
        'page_key', 'meta_title', 'meta_description', 'meta_keywords', 'og_image',
    ];

    /**
     * @return array{meta_title: ?string, meta_description: ?string, meta_keywords: ?string, og_image: ?string}
     */
    public static function metaFor(string $pageKey): array
    {
        $m = static::query()->where('page_key', $pageKey)->first();

        return [
            'meta_title' => $m?->meta_title,
            'meta_description' => $m?->meta_description,
            'meta_keywords' => $m?->meta_keywords,
            'og_image' => $m?->og_image,
        ];
    }
}
