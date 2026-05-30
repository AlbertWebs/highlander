<?php

namespace App\Support;

use App\Models\SafariExperience;
use App\Models\Tour;
use Illuminate\Support\Collection;

class RelatedToursForSafariExperience
{
    /**
     * Linked tours from admin pivot first; keyword matches and featured tours fill remaining slots.
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

    /**
     * When several tours are linked, prefer the one with admin day-by-day rows and drop overview-only duplicates.
     */
    public static function displayToursForSafariPage(SafariExperience $safariExperience, Collection $tours): Collection
    {
        $tours = $tours->unique('id')->values();

        if ($tours->isEmpty()) {
            return $tours;
        }

        $withDayByDay = $tours->filter(
            fn (Tour $tour) => $tour->itineraryDays->isNotEmpty()
        )->values();

        if ($withDayByDay->isNotEmpty()) {
            return collect([self::pickPrimaryItineraryTour($safariExperience, $withDayByDay)]);
        }

        return $tours->take(1)->values();
    }

    private static function pickPrimaryItineraryTour(SafariExperience $safariExperience, Collection $tours): Tour
    {
        $safariSlug = strtolower((string) $safariExperience->slug);

        return $tours->sortByDesc(function (Tour $tour) use ($safariSlug): int {
            $score = $tour->itineraryDays->count() * 10;
            if ($tour->is_featured) {
                $score += 5;
            }
            $tourSlug = strtolower((string) $tour->slug);
            if ($tourSlug === $safariSlug) {
                $score += 100;
            } elseif (str_contains($safariSlug, $tourSlug) || str_contains($tourSlug, $safariSlug)) {
                $score += 40;
            }

            return $score;
        })->first();
    }
}
