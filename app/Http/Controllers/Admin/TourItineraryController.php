<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourItineraryController extends Controller
{
    public function edit(Tour $tour): View
    {
        $tour->load(['itineraryDays' => fn ($q) => $q->orderBy('day_number')]);

        return view('admin.tours.itinerary', compact('tour'));
    }

    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $request->validate([
            'days' => ['nullable', 'array', 'max:50'],
            'days.*.title' => ['nullable', 'string', 'max:255'],
            'days.*.body' => ['nullable', 'string', 'max:20000'],
            'days.*.existing_image' => ['nullable', 'string', 'max:500'],
            'days.*.image' => ['nullable', 'image', 'max:5120'],
        ]);

        $tour->load(['itineraryDays' => fn ($q) => $q->orderBy('day_number')]);
        $oldPaths = $tour->itineraryDays->pluck('image')->filter()->values()->all();

        $rawDays = $request->input('days', []);
        $records = [];
        $finalPaths = [];

        foreach ($rawDays as $i => $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $body = trim((string) ($row['body'] ?? ''));
            $imagePath = null;

            if ($request->hasFile("days.$i.image")) {
                $imagePath = $request->file("days.$i.image")->store('tour-itinerary', 'public');
            } else {
                $existing = trim((string) ($row['existing_image'] ?? ''));
                if ($existing !== '' && self::isAllowedItineraryPath($existing) && in_array($existing, $oldPaths, true)) {
                    $imagePath = $existing;
                }
            }

            if ($imagePath !== null) {
                $finalPaths[] = $imagePath;
            }
            $records[] = [
                'title' => $title,
                'body' => $body !== '' ? $body : null,
                'image' => $imagePath,
            ];
        }

        foreach ($oldPaths as $p) {
            if ($p !== '' && ! in_array($p, $finalPaths, true)) {
                Storage::disk('public')->delete($p);
            }
        }

        DB::transaction(function () use ($tour, $records): void {
            $tour->itineraryDays()->delete();
            foreach ($records as $i => $r) {
                $tour->itineraryDays()->create([
                    'day_number' => $i + 1,
                    'title' => $r['title'],
                    'body' => $r['body'],
                    'image' => $r['image'],
                ]);
            }
        });

        ActivityLog::record('tour.itinerary_updated', $tour->title, $tour);
        $this->forgetHomeCache();

        return redirect()
            ->route('admin.tours.itinerary.edit', $tour)
            ->with('success', __('Itinerary saved.'));
    }

    protected static function isAllowedItineraryPath(string $path): bool
    {
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            return false;
        }

        return str_starts_with($path, 'tour-itinerary/');
    }

    protected function forgetHomeCache(): void
    {
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');
    }
}
