<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Structured content: Mount Kenya introduction (article) + T.C.V Cultural and Farm Experience (tour).
 */
class MountKenyaIntroductionAndTcvItinerarySeeder extends Seeder
{
    private const TOUR_SORT_ORDER = 45;

    public function run(): void
    {
        foreach ($this->payload() as $block) {
            $category = $block['category'] ?? null;

            if ($category === 'Introduction') {
                $this->seedIntroduction($block);
                continue;
            }

            if ($category === 'Itinerary') {
                $this->seedItineraryTour($block);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $block
     */
    private function seedIntroduction(array $block): void
    {
        $title = (string) ($block['title'] ?? '');
        $slug = Str::slug($title);
        $c = $block['content'] ?? [];

        if (! is_array($c)) {
            return;
        }

        $body = '<h2>Climate</h2>'
            .'<p>'.e((string) ($c['climate'] ?? '')).'</p>'
            .'<h2>Flora</h2>'
            .'<p>'.e((string) ($c['flora'] ?? '')).'</p>'
            .'<h2>Cultural significance</h2>'
            .'<p>'.e((string) ($c['cultural_significance'] ?? '')).'</p>'
            .'<h2>Wildlife</h2>'
            .'<p>'.e((string) ($c['wildlife'] ?? '')).'</p>';

        $excerpt = 'Climate, vegetation, culture, and wildlife on Africa’s second-highest peak: an introduction to Mount Kenya.';

        Article::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => $body,
                'featured_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDay(),
                'is_active' => true,
                'meta_title' => $title.' | Highlanders Nature Trails',
                'meta_description' => Str::limit($excerpt, 155),
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $block
     */
    private function seedItineraryTour(array $block): void
    {
        $routeName = (string) ($block['route_name'] ?? '');
        $slug = Str::slug($routeName);
        $durationLabel = (string) ($block['duration'] ?? '');
        $durationDays = self::parseDurationDays($durationLabel) ?? 5;

        $overview = 'A five-day cultural and agricultural journey: Thingira Cultural Village, the Mwea Irrigation Scheme, farm homestays in Kerugoya, coffee and community life, and an ornamental birds visit before returning to Nairobi.';

        $tour = Tour::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $routeName,
                'description' => $overview,
                'price' => 1380.00,
                'duration_days' => $durationDays,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => self::TOUR_SORT_ORDER,
                'meta_title' => $routeName.' | Highlanders Nature Trails',
                'meta_description' => Str::limit(strip_tags($overview), 155),
            ]
        );

        $tour->itineraryDays()->delete();

        $days = $block['itinerary'] ?? [];
        if (! is_array($days)) {
            return;
        }

        foreach (array_values($days) as $idx => $dayRow) {
            if (! is_array($dayRow)) {
                continue;
            }

            $dayNum = (int) ($dayRow['day'] ?? ($idx + 1));
            $dayTitle = (string) ($dayRow['title'] ?? "Day {$dayNum}");
            $body = $this->formatDayBody($dayRow);

            $tour->itineraryDays()->create([
                'day_number' => $dayNum,
                'title' => Str::limit($dayTitle, 255),
                'body' => $body !== '' ? $body : null,
                'image' => null,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $dayRow
     */
    private function formatDayBody(array $dayRow): string
    {
        $parts = [];

        if (! empty($dayRow['activities']) && is_array($dayRow['activities'])) {
            $lines = array_map(static fn (string $a) => '• '.$a, array_values($dayRow['activities']));
            $parts[] = "Activities:\n".implode("\n", $lines);
        }

        if (! empty($dayRow['accommodation'])) {
            $parts[] = 'Accommodation: '.$dayRow['accommodation'];
        }

        if (! empty($dayRow['meals'])) {
            $parts[] = 'Meals: '.$dayRow['meals'];
        }

        return implode("\n\n", array_filter($parts));
    }

    private static function parseDurationDays(string $label): ?int
    {
        if (preg_match('/(\d+)\s*days?/i', $label, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function payload(): array
    {
        return [
            [
                'category' => 'Introduction',
                'title' => 'Exploring Majestic Mount Kenya',
                'content' => [
                    'climate' => 'Day begins with clear blue skies and temperatures near 25°C in lower regions, cooling to 10°C during ascent. Nights above 3,000m can plummet to -10°C.',
                    'flora' => 'Features lush bamboo groves, montane forests, giant groundsels, tussock grass, and towering lobelias. High altitude zones (above 3,800m) consist of hardy alpine herbs.',
                    'cultural_significance' => 'Sacred to the Kikuyu (who call it Kirinyaga), Meru, and Embu communities. Traditionally viewed as the dwelling place of the deity Ngai.',
                    'wildlife' => 'Hyrax, sunbirds, elephants, and buffalos.',
                ],
            ],
            [
                'category' => 'Itinerary',
                'route_name' => 'T.C.V Cultural and Farm Experience',
                'duration' => '5 Days',
                'itinerary' => [
                    [
                        'day' => 1,
                        'title' => 'Arrival & Cultural Immersion at Thingira Cultural Village',
                        'activities' => [
                            'Traditional welcome at Thingira Cultural Village',
                            'Local market shopping for fresh ingredients',
                            'Traditional firewood collection and cooking session',
                            'Evening bonfire with folklore and storytelling from elders',
                        ],
                        'accommodation' => 'Thingira Cultural Village',
                        'meals' => 'Lunch & Dinner (Traditional)',
                    ],
                    [
                        'day' => 2,
                        'title' => 'Agricultural Learning at Mwea Irrigation Scheme',
                        'activities' => [
                            'Rice farming education (planting to harvesting)',
                            'Introduction to aqua farming and irrigation systems',
                            'Guided bird-watching walk',
                            'Relaxation at a local hotel',
                        ],
                        'accommodation' => 'Mwea area hotel',
                        'meals' => 'Breakfast, Lunch & Dinner',
                    ],
                    [
                        'day' => 3,
                        'title' => 'Farm Life and Tea Tour in Kerugoya',
                        'activities' => [
                            'Check-in to a rural farm homestay',
                            'Hands-on tea leaf plucking with locals',
                            'Tea factory visit to observe processing and packaging',
                            'Evening meal prep using farm-fresh produce',
                        ],
                        'accommodation' => 'Rural farm homestay',
                        'meals' => 'Breakfast, Lunch & Dinner (Farm-fresh)',
                    ],
                    [
                        'day' => 4,
                        'title' => 'Coffee Experience and Community Engagement',
                        'activities' => [
                            'Full coffee tour: growing, picking, roasting, and brewing',
                            'Professional coffee tasting (cupping) session',
                            'Optional farm activities: feeding livestock or vegetable picking',
                            'Interaction with the host family',
                        ],
                        'accommodation' => 'Rural farm homestay',
                        'meals' => 'Breakfast, Lunch & Dinner',
                    ],
                    [
                        'day' => 5,
                        'title' => 'Ornamental Birds Tour & Return to Nairobi',
                        'activities' => [
                            'Visit to a homestead specializing in ornamental birds',
                            'Learning about bird rearing and species significance',
                            'Photo session and Q&A with the bird keeper',
                            'Return journey to Nairobi with lunch en route',
                        ],
                        'meals' => 'Breakfast & Lunch',
                    ],
                ],
            ],
        ];
    }
}
