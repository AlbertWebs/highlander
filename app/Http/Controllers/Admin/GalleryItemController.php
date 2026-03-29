<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
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
        $categories = GalleryCategory::query()->orderBy('sort_order')->get();
        $items = GalleryItem::query()
            ->with('category')
            ->when($q, fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(24)
            ->withQueryString();

        // Avoid empty grid when ?page is past the last page (Laravel still treats the page as "valid").
        if ($items->isEmpty() && $items->total() > 0) {
            return redirect()->route('admin.gallery.index', array_merge(
                $request->except('page'),
                ['page' => 1]
            ));
        }

        $stats = [
            'total' => GalleryItem::query()->count(),
            'visible_on_site' => GalleryItem::query()->where('is_active', true)->count(),
        ];

        return view('admin.gallery.index', compact('items', 'q', 'categories', 'stats'));
    }

    public function create(): View
    {
        $categories = GalleryCategory::query()->active()->orderBy('sort_order')->get();

        return view('admin.gallery.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'gallery_category_id' => ['nullable', 'integer', 'exists:gallery_categories,id'],
            'image' => ['required', 'image', 'max:8192'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['image_path'] = $request->file('image')->store('gallery', 'public');
        $item = GalleryItem::query()->create($data);
        ActivityLog::record('gallery.created', $item->title ?? 'Image #'.$item->id, $item);
        $this->forgetHomeCaches();

        return redirect()->route('admin.gallery.index')->with('success', __('Image added.'));
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $ids = $request->input('gallery_category_ids', []);
        $normalized = [];
        if (is_array($ids)) {
            foreach ($ids as $v) {
                $normalized[] = ($v === null || $v === '') ? null : (int) $v;
            }
        }
        $request->merge(['gallery_category_ids' => $normalized]);

        $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:30'],
            'images.*' => ['file', 'image', 'max:8192'],
            'titles' => ['nullable', 'array'],
            'titles.*' => ['nullable', 'string', 'max:255'],
            'gallery_category_ids' => ['nullable', 'array'],
            'gallery_category_ids.*' => ['nullable', 'integer', 'exists:gallery_categories,id'],
        ]);

        $images = $request->file('images', []);
        $titles = $request->input('titles', []);
        $catIds = $request->input('gallery_category_ids', []);

        $maxSort = (int) GalleryItem::query()->max('sort_order');
        $count = 0;
        $createdItems = [];

        foreach ($images as $i => $file) {
            if (! $file) {
                continue;
            }
            $maxSort++;
            $item = GalleryItem::query()->create([
                'gallery_category_id' => $catIds[$i] ?? null,
                'title' => is_array($titles) ? ($titles[$i] ?? null) : null,
                'image_path' => $file->store('gallery', 'public'),
                'is_active' => true,
                'sort_order' => $maxSort,
            ]);
            $item->load('category');
            $createdItems[] = [
                'id' => $item->id,
                'url' => $item->url,
                'title' => $item->title,
                'category_name' => $item->category?->name,
                'is_active' => $item->is_active,
            ];
            $count++;
        }

        ActivityLog::record('gallery.bulk_upload', $count.' images');
        $this->forgetHomeCaches();

        return response()->json([
            'message' => $count === 1 ? __('1 image uploaded.') : __(':count images uploaded.', ['count' => $count]),
            'items' => $createdItems,
        ]);
    }

    public function edit(GalleryItem $galleryItem): View
    {
        $categories = GalleryCategory::query()->active()->orderBy('sort_order')->get();

        return view('admin.gallery.edit', compact('galleryItem', 'categories'));
    }

    public function update(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'gallery_category_id' => ['nullable', 'integer', 'exists:gallery_categories,id'],
            'image' => ['nullable', 'image', 'max:8192'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($galleryItem->image_path);
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }
        $galleryItem->update($data);
        ActivityLog::record('gallery.updated', $galleryItem->title ?? '#'.$galleryItem->id, $galleryItem);
        $this->forgetHomeCaches();

        return redirect()->route('admin.gallery.index')->with('success', __('Image updated.'));
    }

    public function destroy(GalleryItem $galleryItem): RedirectResponse
    {
        Storage::disk('public')->delete($galleryItem->image_path);
        ActivityLog::record('gallery.deleted', $galleryItem->title ?? '#'.$galleryItem->id, $galleryItem);
        $galleryItem->delete();
        $this->forgetHomeCaches();

        return redirect()->route('admin.gallery.index')->with('success', __('Image removed.'));
    }

    public function toggle(GalleryItem $galleryItem): RedirectResponse
    {
        $galleryItem->update(['is_active' => ! $galleryItem->is_active]);
        $this->forgetHomeCaches();

        return back()->with('success', __('Status updated.'));
    }

    private function forgetHomeCaches(): void
    {
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');
    }
}
