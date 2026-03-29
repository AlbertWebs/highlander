<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SafariExperience;
use App\Support\SlugHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SafariExperienceController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $safariExperiences = SafariExperience::query()
            ->when($q, fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.safari.index', compact('safariExperiences', 'q'));
    }

    public function create(): View
    {
        return view('admin.safari.create');
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
        Cache::forget('home_page_v3');

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience created.'));
    }

    public function edit(SafariExperience $safariExperience): View
    {
        return view('admin.safari.edit', compact('safariExperience'));
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
        ActivityLog::record('safari.updated', $safariExperience->title, $safariExperience);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience updated.'));
    }

    public function destroy(SafariExperience $safariExperience): RedirectResponse
    {
        if ($safariExperience->image) {
            Storage::disk('public')->delete($safariExperience->image);
        }
        ActivityLog::record('safari.deleted', $safariExperience->title, $safariExperience);
        $safariExperience->delete();
        Cache::forget('home_page_v3');

        return redirect()->route('admin.safari.index')->with('success', __('Safari experience deleted.'));
    }

    public function toggle(SafariExperience $safariExperience): RedirectResponse
    {
        $safariExperience->update(['is_active' => ! $safariExperience->is_active]);
        Cache::forget('home_page_v3');

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
