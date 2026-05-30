<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SafariExperience;
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
        ActivityLog::record('safari.created', $m->title, $m);
        $this->forgetHomeCache();

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience created.'));
    }

    public function edit(SafariExperience $safariExperience): View
    {
        $safariExperience->load('tours:id');

        return view('admin.safari.edit', [
            'safariExperience' => $safariExperience,
            'tours' => $this->tourOptions($safariExperience),
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
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:5120'],
            'tour_ids' => ['nullable', 'array'],
            'tour_ids.*' => ['integer', 'exists:tours,id'],
        ]);

        if (empty($data['country'])) {
            $data['country'] = null;
        }

        return $data;
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

    protected function forgetHomeCache(): void
    {
        foreach (['home_page_v3', 'home_page_v4', 'home_page_v5', 'home_page_v6', 'home_page_v7', 'home_page_v8', 'home_page_v9', 'home_page_v10', 'home_page_v11', 'home_page_v12'] as $key) {
            Cache::forget($key);
        }
    }
}
