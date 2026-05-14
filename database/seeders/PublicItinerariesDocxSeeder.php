<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Support\DocxItineraryParser;
use App\Support\DocxPlainText;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Imports each mapped {@see public/itineraries} .docx as a {@see Tour} with canonical slug (same URLs as brochure tours).
 *
 * Live-safe: never deletes itinerary days. Only creates day rows when the tour has none yet.
 * Enable via {@see DatabaseSeeder} when env {@code SEED_PUBLIC_ITINERARIES=true}, or run:
 * {@code php artisan db:seed --class=PublicItinerariesDocxSeeder}
 */
class PublicItinerariesDocxSeeder extends Seeder
{
    private const DIR = 'itineraries';

    public function run(): void
    {
        $dir = public_path(self::DIR);
        if (! is_dir($dir)) {
            $this->command?->warn('Missing directory: public/'.self::DIR);

            return;
        }

        $skip = array_flip(array_map('strtolower', config('itinerary_docx_import.skip_basenames_lower', [])));
        $map = config('itinerary_docx_import.files', []);

        foreach (File::files($dir) as $fileInfo) {
            $path = $fileInfo->getPathname();
            if (strtolower($fileInfo->getExtension()) !== 'docx') {
                continue;
            }

            $base = $fileInfo->getFilename();
            $key = strtolower($base);

            if (isset($skip[$key])) {
                $this->command?->line("Skip (intro/non-tour): {$base}");

                continue;
            }

            if (! isset($map[$key])) {
                $this->command?->warn("No config mapping for: {$base} - add to config/itinerary_docx_import.php");

                continue;
            }

            $title = (string) $map[$key]['title'];
            $slug = Str::slug($title);
            $sortOrder = $map[$key]['sort_order'] ?? null;
            $navBucket = (string) ($map[$key]['nav_bucket'] ?? Tour::NAV_SAFARI);
            if (! in_array($navBucket, Tour::NAV_BUCKETS, true)) {
                $navBucket = Tour::NAV_SAFARI;
            }

            $paragraphs = DocxPlainText::paragraphs($path);
            if ($paragraphs === []) {
                $this->command?->warn("Could not read DOCX (empty or invalid): {$base}");

                continue;
            }

            $durationDays = self::parseDurationDays($title) ?? self::parseDurationDays($paragraphs[0] ?? '');
            $plainOverview = self::overviewFromParagraphs($paragraphs);
            $metaSuffix = self::metaSuffixForTitle($title);

            $existing = Tour::query()->where('slug', $slug)->first();

            $payload = [
                'title' => $title,
                'duration_days' => $durationDays,
                'price' => self::suggestedPriceUsd($durationDays),
                'is_active' => true,
                'is_featured' => false,
                'nav_bucket' => $navBucket,
                'meta_title' => $title.' | '.$metaSuffix,
                'meta_description' => Str::limit(strip_tags($plainOverview), 155),
            ];

            if ($existing === null || trim((string) ($existing->description ?? '')) === '') {
                $payload['description'] = Str::limit($plainOverview, 8000);
            }

            if ($existing === null && $sortOrder !== null) {
                $payload['sort_order'] = $sortOrder;
            }

            $tour = Tour::query()->updateOrCreate(
                ['slug' => $slug],
                $payload
            );

            if ($tour->itineraryDays()->exists()) {
                $this->command?->line("Tour already has days, skipped day import: {$title} ({$slug})");

                continue;
            }

            $days = self::dedupeDayNumbers(DocxItineraryParser::splitIntoDays($paragraphs));
            foreach ($days as $row) {
                $dayNum = (int) ($row['day_number'] ?? 1);
                $dayTitle = Str::limit(trim((string) ($row['title'] ?? "Day {$dayNum}")), 255);
                $body = isset($row['body']) && $row['body'] !== '' ? (string) $row['body'] : null;

                $tour->itineraryDays()->create([
                    'day_number' => $dayNum,
                    'title' => $dayTitle,
                    'body' => $body,
                    'image' => null,
                ]);
            }

            $this->command?->info("Imported days from DOCX: {$base} → {$slug} (".count($days).' days)');
        }
    }

    private static function overviewFromParagraphs(array $paragraphs): string
    {
        $take = array_slice($paragraphs, 0, min(6, count($paragraphs)));

        return implode("\n\n", $take);
    }

    private static function metaSuffixForTitle(string $title): string
    {
        if (preg_match('/mount\\s+kenya|naromoru|sirimon|chogoria|burguret|kamweti|timau|lenana|mackinder|shipton|met\\s+station|old\\s+moses/i', $title)) {
            return 'Mount Kenya';
        }

        return (string) config('app.name', 'Highlanders Nature Trails');
    }

    private static function parseDurationDays(?string $label): ?int
    {
        if ($label === null || $label === '') {
            return null;
        }

        if (preg_match('/(\d+)\s*days?/i', $label, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private static function suggestedPriceUsd(?int $days): ?float
    {
        return match ($days) {
            3 => 1450.00,
            4 => 1680.00,
            5 => 1950.00,
            7 => 2750.00,
            default => null,
        };
    }

    /**
     * @param  list<array{day_number: int, title: string, body: ?string}>  $days
     * @return list<array{day_number: int, title: string, body: ?string}>
     */
    private static function dedupeDayNumbers(array $days): array
    {
        $byDay = [];
        foreach ($days as $row) {
            $d = (int) ($row['day_number'] ?? 1);
            if (! isset($byDay[$d])) {
                $byDay[$d] = $row;

                continue;
            }
            $prevBody = $byDay[$d]['body'] ?? '';
            $nextBody = $row['body'] ?? '';
            $merged = trim($prevBody."\n\n".$nextBody);

            $byDay[$d]['body'] = $merged !== '' ? $merged : null;
        }

        ksort($byDay, SORT_NUMERIC);

        return array_values($byDay);
    }
}
