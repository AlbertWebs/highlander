<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Mountain;
use App\Models\SafariExperience;
use App\Models\SafariExperienceImage;
use App\Models\Tour;
use App\Support\SlugHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SafariExperienceController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $safariExperiences = SafariExperience::query()
            ->with(['tours:id,title,slug'])
            ->when($q, fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        if ($safariExperiences->isEmpty() && $safariExperiences->total() > 0) {
            return redirect()->route('admin.safari.index', array_merge(
                $request->except('page'),
                ['page' => 1]
            ));
        }

        $stats = [
            'total' => SafariExperience::query()->count(),
            'visible' => SafariExperience::query()->where('is_active', true)->count(),
        ];

        return view('admin.safari.index', compact('safariExperiences', 'q', 'stats'));
    }

    public function create(): View
    {
        return view('admin.safari.create', [
            'tours' => $this->tourOptions(),
            'mountains' => $this->mountainOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = SlugHelper::unique(SafariExperience::class, 'slug', $data['title']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('safari', 'public');
        }
        $m = SafariExperience::query()->create($data);
        $this->storeGalleryImages($request, $m);
        $m->tours()->sync($request->input('tour_ids', []));
        ActivityLog::record('safari.created', $m->title, $m);
        $this->forgetHomeCache();

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience created.'));
    }

    public function edit(SafariExperience $safariExperience): View
    {
        $safariExperience->load([
            'tours:id',
            'galleryImages:id,safari_experience_id,image,sort_order',
        ]);

        return view('admin.safari.edit', [
            'safariExperience' => $safariExperience,
            'tours' => $this->tourOptions($safariExperience),
            'mountains' => $this->mountainOptions($safariExperience),
        ]);
    }

    public function update(Request $request, SafariExperience $safariExperience): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('title') && $request->string('title') !== $safariExperience->title) {
            $data['slug'] = SlugHelper::unique(SafariExperience::class, 'slug', $data['title'], $safariExperience->id);
        }
        if ($request->hasFile('image')) {
            if ($safariExperience->image) {
                Storage::disk('public')->delete($safariExperience->image);
            }
            $data['image'] = $request->file('image')->store('safari', 'public');
        }
        $safariExperience->update($data);
        $this->removeGalleryImages($request, $safariExperience);
        $this->storeGalleryImages($request, $safariExperience);
        $safariExperience->tours()->sync($request->input('tour_ids', []));
        ActivityLog::record('safari.updated', $safariExperience->title, $safariExperience);
        $this->forgetHomeCache();

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience updated.'));
    }

    public function destroy(SafariExperience $safariExperience): RedirectResponse
    {
        if ($safariExperience->image) {
            Storage::disk('public')->delete($safariExperience->image);
        }
        $safariExperience->load('galleryImages:id,image');
        $safariExperience->galleryImages->each(function (SafariExperienceImage $image): void {
            Storage::disk('public')->delete($image->image);
        });
        ActivityLog::record('safari.deleted', $safariExperience->title, $safariExperience);
        $safariExperience->delete();
        $this->forgetHomeCache();

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience deleted.'));
    }

    public function toggle(SafariExperience $safariExperience): RedirectResponse
    {
        $safariExperience->update(['is_active' => ! $safariExperience->is_active]);
        $this->forgetHomeCache();

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', Rule::in(Tour::HOMEPAGE_COUNTRIES)],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:5120'],
            'tour_ids' => ['nullable', 'array'],
            'tour_ids.*' => ['integer', 'exists:tours,id'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'remove_gallery_image_ids' => ['nullable', 'array'],
            'remove_gallery_image_ids.*' => ['integer', 'exists:safari_experience_images,id'],
        ]);

        if (empty($data['country'])) {
            $data['country'] = null;
        }

        if (empty($data['mountain_id'])) {
            $data['mountain_id'] = null;
        }

        return $data;
    }

    protected function mountainOptions(?SafariExperience $safariExperience = null)
    {
        $mountains = Mountain::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($safariExperience?->mountain_id) {
            $linked = Mountain::query()->find($safariExperience->mountain_id, ['id', 'name']);
            if ($linked instanceof Mountain && ! $mountains->contains('id', $linked->id)) {
                $mountains = $mountains->prepend($linked)->values();
            }
        }

        return $mountains;
    }

    protected function tourOptions(?SafariExperience $safariExperience = null)
    {
        return Tour::query()
            ->active()
            ->where(function ($query) use ($safariExperience): void {
                $query->whereDoesntHave('safariExperiences');

                if ($safariExperience instanceof SafariExperience) {
                    $query->orWhereHas('safariExperiences', function ($linked) use ($safariExperience): void {
                        $linked->whereKey($safariExperience->getKey());
                    });
                }
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'duration_days']);
    }

    protected function storeGalleryImages(Request $request, SafariExperience $safariExperience): void
    {
        $files = $request->file('gallery_images', []);
        if (! is_array($files) || $files === []) {
            return;
        }

        $start = (int) ($safariExperience->galleryImages()->max('sort_order') ?? -1) + 1;
        foreach (array_values($files) as $index => $file) {
            $path = $file->store('safari/gallery', 'public');
            $safariExperience->galleryImages()->create([
                'image' => $path,
                'sort_order' => $start + $index,
            ]);
        }
    }

    protected function removeGalleryImages(Request $request, SafariExperience $safariExperience): void
    {
        $ids = collect($request->input('remove_gallery_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $images = $safariExperience->galleryImages()
            ->whereIn('id', $ids)
            ->get(['id', 'image']);

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }
    }

    protected function forgetHomeCache(): void
    {
        foreach (['home_page_v3', 'home_page_v4', 'home_page_v5', 'home_page_v6', 'home_page_v7', 'home_page_v8', 'home_page_v9', 'home_page_v10', 'home_page_v11', 'home_page_v12'] as $key) {
            Cache::forget($key);
        }
    }
}
