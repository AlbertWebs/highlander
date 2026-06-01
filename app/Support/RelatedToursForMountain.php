<?php

namespace App\Support;

use App\Models\Mountain;
use App\Models\Tour;
use Illuminate\Support\Collection;

class RelatedToursForMountain
{
    /**
     * All active tours associated with this mountain hub (mountain detail pages).
     * Tours explicitly linked in admin (mountain_id) are listed first; the rest follow
     * slug/keyword heuristics. Mount Kenya uses the curated hub slug set.
     *
     * @return Collection<int, Tour>
     */
    public static function allForMountain(Mountain $mountain, int $max = 200): Collection
    {
        $cap = max(1, $max);

        $linked = Tour::query()
            ->active()
            ->where('mountain_id', $mountain->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $heuristic = self::heuristicAllForMountain($mountain, $cap);
        $linkedIds = $linked->pluck('id')->all();
        $heuristicFiltered = $heuristic->whereNotIn('id', $linkedIds)->values();

        return $linked->concat($heuristicFiltered)->take($cap)->values();
    }

    /**
     * Active tours for this mountain, topped up with featured tours when nothing matches.
     *
     * @return Collection<int, Tour>
     */
    public static function get(Mountain $mountain, int $limit = 8): Collection
    {
        $all = self::allForMountain($mountain, max($limit, 200));
        if ($all->isNotEmpty()) {
            return $all->take(max(1, $limit))->values();
        }

        return self::fallbackTours($limit);
    }

    /**
     * @return Collection<int, Tour>
     */
    private static function heuristicAllForMountain(Mountain $mountain, int $max): Collection
    {
        $cap = max(1, $max);

        if ($mountain->slug === 'mount-kenya') {
            return Tour::query()
                ->active()
                ->whereIn('slug', Tour::mountKenyaDestinationHubSlugs())
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit($cap)
                ->get();
        }

        if (in_array($mountain->slug, ['mount-kilimanjaro', 'kilimanjaro'], true)) {
            return Tour::query()
                ->active()
                ->where(function ($q): void {
                    $q->whereIn('slug', Tour::mountKilimanjaroMountainHubSlugs())
                        ->orWhereRaw('LOWER(title) like ?', ['%kilimanjaro%'])
                        ->orWhereRaw('LOWER(COALESCE(description, "")) like ?', ['%kilimanjaro%'])
                        ->orWhereRaw('LOWER(slug) like ?', ['%kilimanjaro%']);
                })
                ->distinct()
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit($cap)
                ->get();
        }

        if ($mountain->slug === 'mount-kilimambogo') {
            return Tour::query()
                ->active()
                ->where(function ($q): void {
                    $q->whereRaw('LOWER(title) like ?', ['%kilimambogo%'])
                        ->orWhereRaw('LOWER(COALESCE(description, "")) like ?', ['%kilimambogo%'])
                        ->orWhereRaw('LOWER(slug) like ?', ['%kilimambogo%'])
                        ->orWhereRaw('LOWER(title) like ?', ['%ol doinyo sabuk%'])
                        ->orWhereRaw('LOWER(COALESCE(description, "")) like ?', ['%ol doinyo sabuk%'])
                        ->orWhereRaw('LOWER(slug) like ?', ['%ol-doinyo-sabuk%']);
                })
                ->distinct()
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit($cap)
                ->get();
        }

        $patterns = self::mountainKeywordPatterns($mountain);
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
     * @return list<string>
     */
    private static function mountainKeywordPatterns(Mountain $mountain): array
    {
        $slug = strtolower(str_replace('_', '-', trim((string) $mountain->slug)));

        $map = [
            'mount-meru' => ['meru', 'mount meru'],
            'meru' => ['meru', 'mount meru'],
            'drakensberg' => ['drakensberg'],
            'rwenzori' => ['rwenzori', 'ruwenzori'],
            'atlas' => ['atlas', 'toubkal'],
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        return self::tokens($mountain);
    }

    /**
     * @return list<string>
     */
    private static function tokens(Mountain $mountain): array
    {
        $raw = strtolower($mountain->name.' '.$mountain->slug);
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
            ->limit(max(1, $limit))
            ->get();
    }
}
