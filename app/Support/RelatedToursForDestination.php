<?php

namespace App\Support;

use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Support\Collection;

class RelatedToursForDestination
{
    /**
     * All active tours associated with this destination hub (for destination detail pages).
     * Tours explicitly linked in admin (destination_id) are listed first; the rest follow
     * keyword patterns on title, description, and slug. Mount Kenya uses the curated hub slug list.
     *
     * @return Collection<int, Tour>
     */
    public static function allForDestination(Destination $destination, int $max = 200): Collection
    {
        $cap = max(1, $max);

        $linked = Tour::query()
            ->active()
            ->where('destination_id', $destination->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $heuristic = self::heuristicAllForDestination($destination, $cap);
        $linkedIds = $linked->pluck('id')->all();
        $heuristicFiltered = $heuristic->whereNotIn('id', $linkedIds)->values();

        return $linked->concat($heuristicFiltered)->take($cap)->values();
    }

    /**
     * @return Collection<int, Tour>
     */
    private static function heuristicAllForDestination(Destination $destination, int $max): Collection
    {
        $cap = max(1, $max);

        if ($destination->slug === 'mount-kenya') {
            return Tour::query()
                ->active()
                ->whereIn('slug', Tour::mountKenyaDestinationHubSlugs())
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit($cap)
                ->get();
        }

        $patterns = self::destinationKeywordPatterns($destination);
        if ($patterns === []) {
            return collect();
        }

        return Tour::query()
            ->active()
            ->where(function ($q) use ($patterns): void {
                foreach ($patterns as $fragment) {
                    $fragment = strtolower(trim($fragment));
                    if ($fragment === '') {
                        continue;
                    }
                    $pat = '%'.$fragment.'%';
                    $q->orWhereRaw('LOWER(title) like ?', [$pat])
                        ->orWhereRaw('LOWER(COALESCE(description, "")) like ?', [$pat])
                        ->orWhereRaw('LOWER(slug) like ?', [$pat]);
                }
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->limit($cap)
            ->get();
    }

    /**
     * Active tours whose title or description match keywords from the destination name/slug,
     * topped up with featured tours when fewer than $limit matches.
     *
     * @return Collection<int, Tour>
     */
    public static function get(Destination $destination, int $limit = 2): Collection
    {
        $all = self::allForDestination($destination, max($limit, 200));
        if ($all->isNotEmpty()) {
            return $all->take(max(1, $limit))->values();
        }

        return self::fallbackTours($limit);
    }

    /**
     * @return list<string>
     */
    private static function destinationKeywordPatterns(Destination $destination): array
    {
        $slug = strtolower(str_replace('_', '-', trim((string) $destination->slug)));

        $map = [
            'masai-mara' => ['masai mara', 'maasai mara', 'masai', 'mara', 'serengeti'],
            'maasai-mara' => ['masai mara', 'maasai mara', 'masai', 'mara', 'serengeti'],
            'okavango' => ['okavango', 'okavango delta'],
            'drakensberg' => ['drakensberg'],
            'zanzibar-coast' => ['zanzibar', 'zanzibar coast'],
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        return self::tokens($destination);
    }

    /**
     * @return list<string>
     */
    private static function tokens(Destination $destination): array
    {
        $raw = strtolower($destination->name.' '.$destination->slug);
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

    private static function fallbackTours(int $limit): Collection
    {
        return Tour::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}
