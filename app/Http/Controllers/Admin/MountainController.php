<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Mountain;
use App\Support\SlugHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MountainController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $mountains = Mountain::query()
            ->when($q, fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.mountains.index', compact('mountains', 'q'));
    }

    public function create(): View
    {
        return view('admin.mountains.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = SlugHelper::unique(Mountain::class, 'slug', $data['name']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('mountains', 'public');
        }
        $m = Mountain::query()->create($data);
        ActivityLog::record('mountain.created', $m->name, $m);

        return redirect()->route('admin.mountains.index')->with('success', __('Mountain created.'));
    }

    public function edit(Mountain $mountain): View
    {
        return view('admin.mountains.edit', compact('mountain'));
    }

    public function update(Request $request, Mountain $mountain): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('name') && $request->string('name') !== $mountain->name) {
            $data['slug'] = SlugHelper::unique(Mountain::class, 'slug', $data['name'], $mountain->id);
        }
        if ($request->hasFile('image')) {
            if ($mountain->image) {
                Storage::disk('public')->delete($mountain->image);
            }
            $data['image'] = $request->file('image')->store('mountains', 'public');
        }
        $mountain->update($data);
        ActivityLog::record('mountain.updated', $mountain->name, $mountain);

        return redirect()->route('admin.mountains.index')->with('success', __('Mountain updated.'));
    }

    public function destroy(Mountain $mountain): RedirectResponse
    {
        if ($mountain->image) {
            Storage::disk('public')->delete($mountain->image);
        }
        ActivityLog::record('mountain.deleted', $mountain->name, $mountain);
        $mountain->delete();

        return redirect()->route('admin.mountains.index')->with('success', __('Mountain deleted.'));
    }

    public function toggle(Mountain $mountain): RedirectResponse
    {
        $mountain->update(['is_active' => ! $mountain->is_active]);

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'elevation_m' => ['nullable', 'integer', 'min:0', 'max:9000'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
