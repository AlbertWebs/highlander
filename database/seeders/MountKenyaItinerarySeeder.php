<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Mount Kenya trekking itineraries (Naromoru, Sirimon traverse, Sirimon out-and-back, Burguret, Chogoria).
 */
class MountKenyaItinerarySeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->itineraries() as $sortOffset => $item) {
            $title = self::cleanCopy($item['title']);
            $slug = Str::slug($title);
            $durationLabel = self::cleanCopy($item['duration']);
            $durationDays = self::parseDurationDays($durationLabel);
            $overview = self::cleanCopy($item['overview']);

            $tour = Tour::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => $overview,
                    'price' => $this->suggestedPriceUsd($durationDays),
                    'duration_days' => $durationDays,
                    'is_active' => true,
                    'is_featured' => $sortOffset === 0,
                    'sort_order' => 20 + $sortOffset,
                    'nav_bucket' => Tour::NAV_EXPLORE_AFRICA,
                    'meta_title' => $title.' | Mount Kenya',
                    'meta_description' => Str::limit(strip_tags($overview), 155),
                ]
            );

            $tour->itineraryDays()->delete();

            foreach ($item['itinerary'] as $idx => $dayRow) {
                $dayNum = (int) ($dayRow['day'] ?? ($idx + 1));
                $dayTitle = self::cleanCopy((string) ($dayRow['title'] ?? "Day {$dayNum}"));
                $body = $this->formatDayBody($dayRow);

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
     * @return list<array{title: string, duration: string, overview: string, itinerary: list<array<string, mixed>>}>
     */
    private function itineraries(): array
    {
        return [
            [
                'title' => '3 Days Naromoru - Naromoru Route',
                'duration' => '3 Days',
                'overview' => 'The Naromoru–Naromoru route is the fastest and most direct trail to Point Lenana (4,985 m). Known for its steep sections and the legendary “Vertical Bog,” this route offers a challenging yet rewarding experience. The trail cuts through dense forest, moorlands, and alpine zones.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Met Station Camp (3,050 m)',
                        'distance' => '9 km',
                        'hiking_time' => '3–4 hours',
                        'description' => 'Depart Nairobi at 8:00 AM and drive to Naromoru Gate. Trek through bamboo and montane forest along a gently rising path.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Met Station – Mackinder’s Camp (4,200 m)',
                        'distance' => 'Approx. 10 km',
                        'hiking_time' => '5–6 hours',
                        'description' => 'Ascend through the infamous “Vertical Bog”, a challenging, steep, and often muddy stretch. The trail then offers stunning views of the Teleki Valley.',
                        'accommodation' => 'Mackinder’s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Summit Attempt – Return to Nairobi',
                        'summit' => 'Point Lenana (4,985 m)',
                        'description' => 'Begin the summit push between 2:30–3:00 AM to reach the peak by sunrise. Descend back to Met Station via Mackinder’s Camp for transfer to Nairobi.',
                    ],
                ],
            ],
            [
                'title' => '4 Days Sirimon – Naromoru Route',
                'duration' => '4 Days',
                'overview' => 'A classic traverse of Mt. Kenya with a gradual ascent via Sirimon and a faster, steeper descent via Naromoru. Offers diverse landscapes including the Teleki Valley and Vertical Bog.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Old Moses Camp (3,300 m)',
                        'distance' => '9 km',
                        'description' => 'Drive north to Sirimon Gate and trek through forest and heather. Spot wildlife such as colobus monkeys and bushbuck.',
                        'accommodation' => 'Old Moses Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Old Moses Camp – Shipton’s Camp (4,250 m)',
                        'distance' => 'Approx. 17 km',
                        'hiking_time' => '6–7 hours',
                        'description' => 'Trek through Mackinder Valley and cross the Ontulili and Liki rivers. See giant groundsel and lobelias.',
                        'accommodation' => 'Shipton’s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Summit Point Lenana (4,985 m) – Descend to Met Station (3,050 m)',
                        'description' => '3:00 AM start for the summit. Descend via the Naromoru route, passing Austrian Hut and the Vertical Bog.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Met Station – Naromoru Gate – Transfer to Nairobi',
                        'description' => 'Final descent through montane forest and transfer back to Nairobi.',
                    ],
                ],
            ],
            [
                'title' => '4 Days Sirimon - Sirimon Route',
                'duration' => '4 Days',
                'overview' => 'One of the most accessible routes, approaching from the drier northwest side. Excellent for acclimatization due to a gradual ascent and descent.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Old Moses Camp (3,300 m)',
                        'distance' => '9 km',
                        'description' => 'Drive to Sirimon Gate (4–5 hours). Trek through montane forest and heather moorland.',
                        'accommodation' => 'Old Moses Camp',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Old Moses Camp – Shipton’s Camp (4,250 m)',
                        'distance' => 'Approx. 17 km',
                        'hiking_time' => '6–7 hours',
                        'description' => 'Trek through Mackinder Valley. Vegetation transitions from moorland to high alpine heath.',
                        'accommodation' => 'Shipton’s Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Acclimatization Day at Shipton’s Camp (4,200 m)',
                        'hiking_time' => '9:00 AM – 12:00 PM',
                        'description' => 'Optional hike to Hausberg Col (4,600 m) or nearby tarns. Rest and summit briefing in the afternoon.',
                        'accommodation' => 'Shipton’s Camp',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Attempt – Descend to Old Moses – Transfer to Nairobi',
                        'description' => 'Start at 3:00 AM for Point Lenana (4,985 m). Descend back to Shipton’s then down to Old Moses for the drive to Nairobi.',
                    ],
                ],
            ],
            [
                'title' => '5 Days Burguret Route',
                'duration' => '5 Days',
                'overview' => 'A secluded and pristine approach from the western flank. Features dense forest and bamboo thickets; it is one of the least used routes.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Gathiuru Forest – Camp 1 (3,000 m)',
                        'description' => 'Drive to Gathiuru Forest Station. Trek through equatorial forest where buffalo and colobus monkeys may be seen.',
                        'accommodation' => 'Camp 1 (forest edge)',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Camp 1 – Highland Castle Camp (3,700 m)',
                        'hiking_time' => '5–6 hours',
                        'description' => 'Traverse heather-covered ridges in the moorland zone to reach the rocky Highland Castle outcrop.',
                        'accommodation' => 'Highland Castle Camp',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Highland Castle – Shipton’s or Austrian Hut (4,200–4,790 m)',
                        'hiking_time' => '5–7 hours',
                        'description' => 'Connect with the Summit Circuit Trail. Circle the central massif with views of Batian and Nelion.',
                        'accommodation' => 'Shipton’s Camp or Austrian Hut',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Point Lenana (4,985 m) – Descend to Met Station (3,050 m)',
                        'description' => '3:00–4:00 AM start. Descend via the Naromoru route through the Vertical Bog.',
                        'accommodation' => 'Met Station',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Met Station – Naromoru Gate – Transfer to Nairobi',
                        'description' => 'Final trek through montane forest and transfer to Nairobi.',
                    ],
                ],
            ],
            [
                'title' => '5 Days Chogoria - Chogoria Route',
                'duration' => '5 Days',
                'overview' => 'A captivating journey along the lush eastern slopes. Features waterfalls, alpine lakes, and wide valleys with a gradual ascent.',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Nairobi – Mt. Kenya Bandas (3,000 m)',
                        'distance' => '5 km hike',
                        'description' => 'Drive to Chogoria town and perform a gentle acclimatization walk through forest and bamboo.',
                        'accommodation' => 'Mt. Kenya Bandas',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Mt. Kenya Bandas – Lake Ellis (3,400 m)',
                        'distance' => 'Approx. 9 km',
                        'hiking_time' => '5–6 hours',
                        'description' => 'Ascend through dense forest and moorland past a waterfall to the shores of Lake Ellis.',
                        'accommodation' => 'Lake Ellis',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Lake Ellis – Mintos Camp (4,200 m)',
                        'distance' => 'Approx. 14 km',
                        'hiking_time' => '6–7 hours',
                        'description' => 'Follow the ridge with views over Gorges Valley to reach Mintos Camp on a cliff edge.',
                        'accommodation' => 'Mintos',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Summit Attempt (Point Lenana – 4,985 m) – Descend to Road Head (3,300 m)',
                        'description' => 'Pre-dawn ascent for sunrise at the summit. Descend through Gorges Valley trail to Road Head.',
                        'accommodation' => 'Road Head',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Road Head – Mt. Kenya Bandas – Transfer to Nairobi',
                        'distance' => '9 km',
                        'description' => 'Trek down through the forest to return to the Bandas for vehicle transfer.',
                    ],
                ],
            ],
        ];
    }

    private function suggestedPriceUsd(?int $days): ?float
    {
        return match ($days) {
            3 => 1450.00,
            4 => 1680.00,
            5 => 1950.00,
            default => null,
        };
    }

    /**
     * @param  array<string, mixed>  $dayRow
     */
    private function formatDayBody(array $dayRow): string
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
            $parts[] = $label.': '.self::cleanCopy((string) $dayRow[$key]);
        }

        $desc = isset($dayRow['description']) ? self::cleanCopy((string) $dayRow['description']) : '';

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

    public static function cleanCopy(string $text): string
    {
        $text = preg_replace('/\s*\[cite:\s*[0-9,\s]+\]/i', '', $text) ?? $text;
        $text = preg_replace('/\$([^$]+?)\\text\{m\}\$/u', '$1 m', $text) ?? $text;
        $text = str_replace('\\text{m}', '', $text);
        $text = preg_replace('/\(([\d,]+)m\)/u', '($1 m)', $text) ?? $text;
        $text = preg_replace("/[ \t]+/u", ' ', $text) ?? $text;

        return trim($text);
    }
}
