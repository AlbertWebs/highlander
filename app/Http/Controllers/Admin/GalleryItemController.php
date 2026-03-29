<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryItemController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $items = GalleryItem::query()
            ->when($q, fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(24)
            ->withQueryString();

        return view('admin.gallery.index', compact('items', 'q'));
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['required', 'image', 'max:8192'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['image_path'] = $request->file('image')->store('gallery', 'public');
        $item = GalleryItem::query()->create($data);
        ActivityLog::record('gallery.created', $item->title ?? 'Image #'.$item->id, $item);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.gallery.index')->with('success', __('Image added.'));
    }

    public function edit(GalleryItem $galleryItem): View
    {
        return view('admin.gallery.edit', compact('galleryItem'));
    }

    public function update(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:8192'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($galleryItem->image_path);
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }
        $galleryItem->update($data);
        ActivityLog::record('gallery.updated', $galleryItem->title ?? '#'.$galleryItem->id, $galleryItem);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.gallery.index')->with('success', __('Image updated.'));
    }

    public function destroy(GalleryItem $galleryItem): RedirectResponse
    {
        Storage::disk('public')->delete($galleryItem->image_path);
        ActivityLog::record('gallery.deleted', $galleryItem->title ?? '#'.$galleryItem->id, $galleryItem);
        $galleryItem->delete();
        Cache::forget('home_page_v3');

        return redirect()->route('admin.gallery.index')->with('success', __('Image removed.'));
    }

    public function toggle(GalleryItem $galleryItem): RedirectResponse
    {
        $galleryItem->update(['is_active' => ! $galleryItem->is_active]);
        Cache::forget('home_page_v3');

        return back()->with('success', __('Status updated.'));
    }
}
