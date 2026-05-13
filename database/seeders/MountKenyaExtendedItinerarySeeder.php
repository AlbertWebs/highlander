<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Additional Mount Kenya treks not covered by {@see MountKenyaItinerarySeeder}.
 *
 * Note: "3 Days Naromoru - Naromoru Route" is intentionally omitted here because it is already seeded there.
 */
class MountKenyaExtendedItinerarySeeder extends Seeder
{
    private const SORT_BASE = 35;

    public function run(): void
    {
        foreach ($this->itineraries() as $sortOffset => $item) {
            $title = MountKenyaItinerarySeeder::cleanCopy($item['route_name']);
            $slug = Str::slug($title);
            $durationLabel = MountKenyaItinerarySeeder::cleanCopy($item['duration']);
            $durationDays = self::parseDurationDays($durationLabel);
            $overview = MountKenyaItinerarySeeder::cleanCopy($item['summary']);

            $tour = Tour::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => $overview,
                    'price' => self::suggestedPriceUsd($durationDays),
                    'duration_days' => $durationDays,
                    'is_active' => true,
                    'is_featured' => false,
                    'sort_order' => self::SORT_BASE + $sortOffset,
                    'meta_title' => $title.' | Mount Kenya',
                    'meta_description' => Str::limit(strip_tags($overview), 155),
                ]
            );

            $tour->itineraryDays()->delete();

            foreach ($item['itinerary'] as $idx => $dayRow) {
                $dayRow = self::normalizeDayRow($dayRow);
                $dayNum = self::normalizeDayNumber($dayRow['day'] ?? null, $idx);
                $dayTitle = MountKenyaItinerarySeeder::cleanCopy((string) ($dayRow['title'] ?? "Day {$dayNum}"));
                $body = self::formatDayBody($dayRow);

                $tour->itineraryDays()->create([
                    'day_number' => $dayNum,
                    'title' => Str::limit($dayTitle, 255),
                    'body' => $body !== '' ? $body : null,
                    'image' => null,
                ]);
            }
        }
    }

    /**
     * Map JSON "duration" (hiking/activity time) to hiking_time for body formatting.
     *
     * @param  array<string, mixed>  $dayRow
     * @return array<string, mixed>
     */
    private static function normalizeDayRow(array $dayRow): array
    {
        if (! empty($dayRow['duration']) && empty($dayRow['hiking_time'])) {
            $dayRow['hiking_time'] = $dayRow['duration'];
        }

        return $dayRow;
    }

    private static function normalizeDayNumber(mixed $day, int $idx): int
    {
        if (is_int($day)) {
            return max(1, $day);
        }
        if (is_string($day)) {
            $day = trim($day);
            if ($day !== '' && ctype_digit($day)) {
                return max(1, (int) $day);
            }
            if (preg_match('/^(\d+)/u', $day, $m)) {
                return max(1, (int) $m[1]);
            }
        }

        return $idx + 1;
    }

    /**
     * @param  array<string, mixed>  $dayRow
     */
    private static function formatDayBody(array $dayRow): string
    {
        $parts = [];

        foreach (['distance', 'hiking_time', 'summit', 'accommodation'] as $key) {
            if (empty($dayRow[$key])) {
                continue;
            }
            $label = match ($key) {
                'distance' => 'Distance',
                'hiking_time' => 'Hiking time',
                'summit' => 'Summit',
                'accommodation' => 'Accommodation',
                default => $key,
            };
            $parts[] = $label.': '.MountKenyaItinerarySeeder::cleanCopy((string) $dayRow[$key]);
        }

        $desc = isset($dayRow['description']) ? MountKenyaItinerarySeeder::cleanCopy((string) $dayRow['description']) : '';

        if ($parts !== [] && $desc !== '') {
            return implode("\n", $parts)."\n\n".$desc;
        }

        if ($desc !== '') {
            return $desc;
        }

        return implode("\n", $parts);
    }

    private static function parseDurationDays(string $label): ?int
    {
        if (preg_match('/(\d+)\s*days?/i', $label, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private static function suggestedPriceUsd(?int $days): ?float
    {
        return match ($days) {
            5 => 2050.00,
            7 => 2750.00,
            default => null,
        };
    }

    /**
     * @return list<array{route_name: string, duration: string, summary: string, itinerary: list<array<string, mixed>>}>
     */
    private function itineraries(): array
    {
        return [
            [
                'route_name' => '5 Days Sirimon - Chogoria Route',
                'duration' => '5 Days',
                'summary' => 'A stunning traverse across both the dry western and lush eastern slopes, featuring Gorges Valley and waterfalls.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi - Old Moses Camp (3,300 m)',
                        'distance' => '9 km',
                        'duration' => '4-5 hours road travel + hike',
                        'description' => 'Travel to Sirimon Gate and hike through forest and heather.',
                        'accommodation' => 'Old Moses Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Old Moses Camp to Shipton\'s Camp (4,250 m)',
                        'distance' => '17 km',
                        'duration' => '6-7 hours',
                        'description' => 'Trek through Mackinder Valley, crossing Liki and Ontulili rivers.',
                        'accommodation' => 'Shipton\'s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Acclimatization day at Shipton camp (4,200 m)',
                        'distance' => '3–6 km round trip',
                        'duration' => '2–3 hours',
                        'description' => 'Optional light trek to Hausberg Col (4,600 m) or nearby tarns.',
                        'accommodation' => 'Shipton\'s Camp',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Lenana attempt (4,985 m)',
                        'duration' => '7 hours (descent)',
                        'description' => 'Sunrise at Point Lenana, followed by a descent through Gorges Valley to Meru Bandas.',
                        'accommodation' => 'Meru Bandas',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Meru Bandas (3,000 m) - Chogoria Gate',
                        'distance' => '7 km',
                        'description' => 'Short hike to pick-up point and transfer to Nairobi.',
                    ],
                ],
            ],
            [
                'route_name' => '5 Days Kamweti Route',
                'duration' => '5 Days',
                'summary' => 'A remote and wild approach from the southern forested slopes through thick bamboo and unspoiled wilderness.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Kamweti Forest Station – Forest Edge Camp (3,000 m)',
                        'description' => 'Drive south to Kamweti and trek through dense indigenous forest.',
                        'accommodation' => 'Forest Edge Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Forest Edge Camp – Thegi Camp (3,600 m)',
                        'duration' => '5–6 hours',
                        'description' => 'Climb through high moorlands along less-defined elephant trails.',
                        'accommodation' => 'Thegi Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Thegi Camp – Austrian Hut (4,790 m)',
                        'duration' => '6–7 hours',
                        'description' => 'Ascend toward Austrian Hut, located beside Lewis Glacier.',
                        'accommodation' => 'Austrian Hut',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Point Lenana (4,985 m) – Descend to Met Station (3,050 m)',
                        'description' => 'Sunrise summit and descent via Naromoru route and Vertical Bog.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Met Station – Naromoru Gate – Transfer to Nairobi',
                        'distance' => '9 km',
                        'description' => 'Final descent through the forest to the gate.',
                    ],
                ],
            ],
            [
                'route_name' => '5 Days Timau Route',
                'duration' => '5 Days',
                'summary' => 'A northern approach with wide open moorlands and a gentle gradient, ideal for gradual acclimatization.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Timau Gate – Marania Camp (3,300 m)',
                        'distance' => '10 km',
                        'description' => 'Drive north to Timau Gate and hike through rolling moorland.',
                        'accommodation' => 'Marania Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Marania Camp – Majors Camp (3,700 m)',
                        'duration' => '5–6 hours',
                        'description' => 'Gentle ascent across wide ridges filled with alpine flora.',
                        'accommodation' => 'Majors Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Majors Camp – Simba Tarn – Austrian Hut (4,790 m)',
                        'duration' => '6–7 hours',
                        'description' => 'Climb to Simba Tarn and join the Summit Circuit Trail.',
                        'accommodation' => 'Austrian Hut',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Point Lenana (4,985 m) – Descend to Met Station (3,050 m)',
                        'description' => 'Summit push at 4:00 AM followed by descent through the Vertical Bog.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Met Station – Naromoru Gate – Transfer to Nairobi',
                        'description' => 'Final descent and return journey to Nairobi.',
                    ],
                ],
            ],
            [
                'route_name' => '7 Days Mount Kenya Peaks Circuit',
                'duration' => '7 Days',
                'summary' => 'The most comprehensive trekking experience, looping around the three main summits with 360-degree views.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Old Moses Camp (3,300 m)',
                        'distance' => '9 km',
                        'description' => 'Drive to Sirimon Gate and trek forest and moorland zones.',
                        'accommodation' => 'Old Moses Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Old Moses – Shipton’s Camp (4,250 m)',
                        'distance' => '17 km',
                        'duration' => '6–7 hours',
                        'description' => 'Trek up Mackinder Valley into the high alpine zone.',
                        'accommodation' => 'Shipton’s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Shipton’s – Hausberg Col – Two Tarn – Kami Camp (4,450 m)',
                        'distance' => '3-4 km',
                        'duration' => '4–5 hours',
                        'description' => 'Technical circuit section over Hausberg Col (4,590 m) to Two Tarn basin.',
                        'accommodation' => 'Kami Camp',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Kami Camp – Simba Col – Tooth Col – Austrian Hut (4,790 m)',
                        'duration' => '4–6 hours',
                        'description' => 'Ascend steep scree and circle beneath the western faces.',
                        'accommodation' => 'Austrian Hut',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Summit Point Lenana (4,985 m) – Lake Michaelson (4,000 m)',
                        'description' => 'Sunrise summit and descent into Gorges Valley to Lake Michaelson.',
                        'accommodation' => 'Lake Michaelson (Camping)',
                    ],
                    [
                        'day' => 6,
                        'title' => 'Lake Michaelson – Meru Mount Kenya Bandas (3,000 m)',
                        'duration' => '6–7 hours',
                        'description' => 'Continue circuit through lower Gorges Valley to Meru Bandas.',
                        'accommodation' => 'Meru Bandas',
                    ],
                    [
                        'day' => 7,
                        'title' => 'Meru Bandas – Chogoria Gate – Transfer to Nairobi',
                        'distance' => '7 km',
                        'description' => 'Final walk through bamboo forest and transfer to Nairobi.',
                    ],
                ],
            ],
            [
                'route_name' => '7 Days Naromoru Route – Technical Climbing Expedition',
                'duration' => '7 Days',
                'summary' => 'Tailored for technical climbers targeting Batian and Nelion, with built-in acclimatization and flexible summit days.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Met Station Camp (3,050 m)',
                        'distance' => '9 km',
                        'duration' => '3–4 hours',
                        'description' => 'Drive to Naromoru Gate and warm-up hike through bamboo zones.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Met Station – Mackinder’s Camp (4,200 m)',
                        'distance' => '10 km',
                        'duration' => '5–6 hours',
                        'description' => 'Trek through Vertical Bog into Teleki Valley.',
                        'accommodation' => 'Mackinder’s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Mackinder’s Camp – Austrian Hut (4,790 m)',
                        'distance' => '5 km',
                        'duration' => '3–4 hours',
                        'description' => 'Hike to technical climbing base with acclimatization trek to Pt. Lenana.',
                        'accommodation' => 'Austrian Hut',
                    ],
                    [
                        'day' => '4–5',
                        'title' => 'Technical Climb – Batian or Nelion (5,199 m)',
                        'description' => 'Technical ascent of Batian or Nelion; requires prior technical experience.',
                        'accommodation' => 'High bivy or Mackinder’s Camp',
                    ],
                    [
                        'day' => 6,
                        'title' => 'Mackinder’s Camp – Met Station Camp',
                        'duration' => '4–5 hours',
                        'description' => 'Descend back through Teleki Valley and the bog.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 7,
                        'title' => 'Met Station – Naromoru Gate – Transfer to Nairobi',
                        'description' => 'Final leg to park gate and drive back to Nairobi.',
                    ],
                ],
            ],
        ];
    }
}
