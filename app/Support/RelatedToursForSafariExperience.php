<?php

namespace App\Support;

use App\Models\SafariExperience;
use App\Models\Tour;
use Illuminate\Support\Collection;

class RelatedToursForSafariExperience
{
    /**
     * Active tours whose title or description match keywords from the safari style title/slug,
     * topped up with featured tours when fewer than $limit matches.
     */
    public static function get(SafariExperience $safariExperience, int $limit = 2): Collection
    {
        $linked = $safariExperience->tours()
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();

        if ($linked->count() >= $limit) {
            return $linked;
        }

        $tokens = self::tokens($safariExperience);

        if ($tokens === []) {
            return $linked->concat(
                self::fallbackTours($limit, $linked->pluck('id')->all(), $limit - $linked->count())
            )->values();
        }

        $matched = Tour::query()
            ->active()
            ->where(function ($q) use ($tokens): void {
                foreach ($tokens as $t) {
                    $pat = '%'.$t.'%';
                    $q->orWhere('title', 'like', $pat)->orWhere('description', 'like', $pat);
                }
            })
            ->whereNotIn('id', $linked->pluck('id'))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit - $linked->count())
            ->get();

        $combined = $linked->concat($matched)->values();
        if ($combined->count() >= $limit) {
            return $combined->take($limit)->values();
        }

        return $combined->concat(
            self::fallbackTours($limit, $combined->pluck('id')->all(), $limit - $combined->count())
        )->values();
    }

    /**
     * @return list<string>
     */
    private static function tokens(SafariExperience $safariExperience): array
    {
        $raw = strtolower($safariExperience->title.' '.$safariExperience->slug);
        $parts = preg_split('/[\s\-_,.&]+/', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $stop = [
            'the', 'and', 'for', 'with', 'our', 'to', 'a', 'an', 'of', 'in', 'on', 'at', 'by', 'from', 'or', 'as',
            'day', 'days', 'night', 'nights', 'trek', 'trekking', 'hike', 'hiking', 'route', 'tour', 'tours',
            'experience', 'adventures', 'adventure', 'itinerary', 'private', 'guided', 'group', 'safari',
            'mount', 'mountain', 'mountains', 'peak', 'summit', 'trail', 'via',
        ];

        $out = [];
        foreach ($parts as $p) {
            $p = preg_replace('/[^a-z0-9]/', '', $p) ?? '';
            if (strlen($p) < 3) {
                continue;
            }
            if (in_array($p, $stop, true)) {
                continue;
            }
            $out[] = $p;
        }

        return array_values(array_unique($out));
    }

    private static function fallbackTours(int $limit, array $excludeIds = [], ?int $take = null): Collection
    {
        $need = $take ?? $limit;

        return Tour::query()
            ->active()
            ->whereNotIn('id', $excludeIds)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($need)
            ->get();
    }
}
