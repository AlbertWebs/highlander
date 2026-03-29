<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Destination;
use App\Support\SlugHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $destinations = Destination::query()
            ->when($q, fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        if ($destinations->isEmpty() && $destinations->total() > 0) {
            return redirect()->route('admin.destinations.index', array_merge(
                $request->except('page'),
                ['page' => 1]
            ));
        }

        $stats = [
            'total' => Destination::query()->count(),
            'visible' => Destination::query()->where('is_active', true)->count(),
        ];

        return view('admin.destinations.index', compact('destinations', 'q', 'stats'));
    }

    public function create(): View
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = SlugHelper::unique(Destination::class, 'slug', $data['name']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }
        $m = Destination::query()->create($data);
        ActivityLog::record('destination.created', $m->name, $m);
        $this->forgetHomeCache();

        return redirect()->route('admin.destinations.index')->with('success', __('Destination created.'));
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('name') && $request->string('name') !== $destination->name) {
            $data['slug'] = SlugHelper::unique(Destination::class, 'slug', $data['name'], $destination->id);
        }
        if ($request->hasFile('image')) {
            if ($destination->image) {
                Storage::disk('public')->delete($destination->image);
            }
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }
        $destination->update($data);
        ActivityLog::record('destination.updated', $destination->name, $destination);
        $this->forgetHomeCache();

        return redirect()->route('admin.destinations.index')->with('success', __('Destination updated.'));
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }
        ActivityLog::record('destination.deleted', $destination->name, $destination);
        $destination->delete();
        Cache::forget('home_page_v3');

        return redirect()->route('admin.destinations.index')->with('success', __('Destination deleted.'));
    }

    public function toggle(Destination $destination): RedirectResponse
    {
        $destination->update(['is_active' => ! $destination->is_active]);
        $this->forgetHomeCache();

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    protected function forgetHomeCache(): void
    {
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');
    }
}
