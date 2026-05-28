<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Destination;
use App\Models\Mountain;
use App\Models\SafariExperience;
use App\Models\Tour;
use App\Support\SlugHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $unassignedOnly = $request->boolean('unassigned');
        $perPage = (int) $request->input('per_page', 30);
        $perPage = in_array($perPage, [10, 15, 25, 30, 50, 100], true) ? $perPage : 30;

        $tours = Tour::query()
            ->with(['mountain', 'destination', 'safariExperiences:id,title'])
            ->withCount('itineraryDays')
            ->when($unassignedOnly, fn ($query) => $query->whereDoesntHave('safariExperiences'))
            ->when($q, function ($query) use ($q): void {
                $like = '%'.$q.'%';
                $query->where(function ($inner) use ($like): void {
                    $inner->where('title', 'like', $like)
                        ->orWhere('slug', 'like', $like);
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $safariStyles = SafariExperience::query()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('admin.tours.index', compact('tours', 'q', 'perPage', 'safariStyles', 'unassignedOnly'));
    }

    public function create(): View
    {
        return view('admin.tours.create', $this->hubFormData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['featured_media_type'] = $request->string('featured_media_type')->toString() === 'video' ? 'video' : 'image';
        $data['featured_video_url'] = $data['featured_media_type'] === 'video'
            ? ($data['featured_video_url'] ?? null)
            : null;
        $data['slug'] = SlugHelper::unique(Tour::class, 'slug', $data['title']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tours', 'public');
        }
        $tour = Tour::query()->create($data);
        $tour->safariExperiences()->sync($request->input('safari_experience_ids', []));
        ActivityLog::record('tour.created', $tour->title, $tour);
        $this->forgetHomeCache();

        return redirect()->route('admin.tours.index')->with('success', __('Tour created.'));
    }

    public function show(Tour $tour): View
    {
        $tour->load(['itineraryDays' => fn ($q) => $q->orderBy('day_number')]);

        return view('admin.tours.show', compact('tour'));
    }

    public function edit(Tour $tour): View
    {
        return view('admin.tours.edit', array_merge($this->hubFormData($tour), compact('tour')));
    }

    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['featured_media_type'] = $request->string('featured_media_type')->toString() === 'video' ? 'video' : 'image';
        $data['featured_video_url'] = $data['featured_media_type'] === 'video'
            ? ($data['featured_video_url'] ?? null)
            : null;
        if ($request->filled('title') && $request->string('title') !== $tour->title) {
            $data['slug'] = SlugHelper::unique(Tour::class, 'slug', $data['title'], $tour->id);
        }
        if ($request->hasFile('image')) {
            if ($tour->image) {
                Storage::disk('public')->delete($tour->image);
            }
            $data['image'] = $request->file('image')->store('tours', 'public');
        }
        $tour->update($data);
        $tour->safariExperiences()->sync($request->input('safari_experience_ids', []));
        ActivityLog::record('tour.updated', $tour->title, $tour);
        $this->forgetHomeCache();

        return redirect()->route('admin.tours.index')->with('success', __('Tour updated.'));
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        if ($tour->image) {
            Storage::disk('public')->delete($tour->image);
        }
        ActivityLog::record('tour.deleted', $tour->title, $tour);
        $tour->delete();
        $this->forgetHomeCache();

        return redirect()->route('admin.tours.index')->with('success', __('Tour deleted.'));
    }

    public function toggle(Tour $tour): RedirectResponse
    {
        $tour->update(['is_active' => ! $tour->is_active]);
        $this->forgetHomeCache();

        return back()->with('success', __('Status updated.'));
    }

    /**
     * Quick update of main-nav menu flags from the tours index table.
     */
    public function updateMenus(Request $request, Tour $tour): RedirectResponse
    {
        $navSafari = $request->boolean('nav_safari');
        $navMountain = $request->boolean('nav_mountain_safari');
        $navExplore = $request->boolean('nav_explore_africa');

        $tour->update([
            'nav_safari' => $navSafari,
            'nav_mountain_safari' => $navMountain,
            'nav_explore_africa' => $navExplore,
        ]);
        ActivityLog::record('tour.updated', $tour->title.' (menus)', $tour);
        $this->forgetHomeCache();

        return back()->with('success', __('Menus updated for :title.', ['title' => $tour->title]));
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'featured_media_type' => ['nullable', 'in:image,video'],
            'featured_video_url' => ['nullable', 'string', 'max:2048', 'required_if:featured_media_type,video'],
            'image' => ['nullable', 'image', 'max:5120'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'destination_id' => ['nullable', 'integer', 'exists:destinations,id'],
            'safari_experience_ids' => ['nullable', 'array'],
            'safari_experience_ids.*' => ['integer', 'exists:safari_experiences,id'],
        ]);

        return $data;
    }

    /**
     * @return array{mountains: Collection<int, Mountain>, destinations: Collection<int, Destination>, safariExperiences: Collection<int, SafariExperience>}
     */
    protected function hubFormData(?Tour $tour = null): array
    {
        $mountains = Mountain::forMainMenu();

        if ($tour !== null && $tour->mountain_id) {
            $linked = Mountain::query()->find($tour->mountain_id);
            if ($linked instanceof Mountain && ! $mountains->contains('id', $linked->id)) {
                $mountains = $mountains->prepend($linked)->values();
            }
        }

        return [
            'mountains' => $mountains,
            'destinations' => Destination::query()->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'slug']),
            'safariExperiences' => SafariExperience::query()
                ->orderBy('sort_order')
                ->orderBy('title')
                ->get(['id', 'title', 'slug', 'duration']),
        ];
    }

    protected function forgetHomeCache(): void
    {
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');
        Cache::forget('home_page_v7');
    }
}
