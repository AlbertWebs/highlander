<?php

namespace App\Support;

use App\Models\TourItineraryDay;

/**
 * Heuristic split of DOCX paragraphs into day rows for {@see TourItineraryDay}.
 *
 * @return list<array{day_number: int, title: string, body: ?string}>
 */
final class DocxItineraryParser
{
    /**
     * Lines starting a new day block, e.g. "Day 1:", "DAY 2 – Nairobi", "Day 3 - Camp".
     */
    private const DAY_HEADER = '/^(day)\s*(\d+)\s*(?:[\:\.\-\x{2013}\x{2014}]+\s*)?(.*)$/iu';

    /**
     * @param  list<string>  $paragraphs
     * @return list<array{day_number: int, title: string, body: ?string}>
     */
    public static function splitIntoDays(array $paragraphs): array
    {
        if ($paragraphs === []) {
            return [];
        }

        $blocks = [];
        $current = null;

        foreach ($paragraphs as $para) {
            if (preg_match(self::DAY_HEADER, $para, $m)) {
                if ($current !== null) {
                    $blocks[] = $current;
                }
                $dayNum = max(1, (int) $m[2]);
                $rest = trim((string) ($m[3] ?? ''));
                $current = [
                    'day_number' => $dayNum,
                    'title' => $rest !== '' ? $rest : 'Day '.$dayNum,
                    'lines' => [],
                ];
            } else {
                if ($current === null) {
                    $current = [
                        'day_number' => 1,
                        'title' => 'Itinerary',
                        'lines' => [],
                    ];
                }
                $current['lines'][] = $para;
            }
        }

        if ($current !== null) {
            $blocks[] = $current;
        }

        $out = [];
        foreach ($blocks as $b) {
            $bodyLines = array_filter($b['lines'], static fn (string $l): bool => $l !== '');
            $body = $bodyLines === [] ? null : implode("\n\n", $bodyLines);

            $out[] = [
                'day_number' => (int) $b['day_number'],
                'title' => (string) $b['title'],
                'body' => $body,
            ];
        }

        if ($out === []) {
            return [[
                'day_number' => 1,
                'title' => 'Itinerary',
                'body' => implode("\n\n", $paragraphs),
            ]];
        }

        return $out;
    }
}
