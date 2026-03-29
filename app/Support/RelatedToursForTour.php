<?php

namespace App\Support;

use App\Models\Tour;
use Illuminate\Support\Collection;

class RelatedToursForTour
{
    /**
     * Other active tours whose title or description match keywords from this tour,
     * topped up with featured tours when fewer than $limit matches.
     */
    public static function get(Tour $exclude, int $limit = 5): Collection
    {
        $tokens = self::tokens($exclude);

        if ($tokens === []) {
            return self::fallbackTours($exclude, $limit);
        }

        $matched = Tour::query()
            ->active()
            ->whereKeyNot($exclude->getKey())
            ->where(function ($q) use ($tokens): void {
                foreach ($tokens as $t) {
                    $pat = '%'.$t.'%';
                    $q->orWhere('title', 'like', $pat)->orWhere('description', 'like', $pat);
                }
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();

        if ($matched->count() >= $limit) {
            return $matched;
        }

        $needed = $limit - $matched->count();
        $more = Tour::query()
            ->active()
            ->whereKeyNot($exclude->getKey())
            ->whereNotIn('id', $matched->pluck('id'))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($needed)
            ->get();

        return $matched->concat($more)->values();
    }

    /**
     * @return list<string>
     */
    private static function tokens(Tour $tour): array
    {
        $raw = strtolower($tour->title.' '.$tour->slug);
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

    private static function fallbackTours(Tour $exclude, int $limit): Collection
    {
        return Tour::query()
            ->active()
            ->whereKeyNot($exclude->getKey())
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}
