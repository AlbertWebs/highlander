<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SeoMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SeoMetaController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $items = SeoMeta::query()
            ->when($q, fn ($query) => $query->where('page_key', 'like', '%'.$q.'%'))
            ->orderBy('page_key')
            ->paginate(20)
            ->withQueryString();

        return view('admin.seo.index', compact('items', 'q'));
    }

    public function create(): View
    {
        return view('admin.seo.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }
        $m = SeoMeta::query()->create($data);
        ActivityLog::record('seo.created', $m->page_key, $m);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.seo.index')->with('success', __('SEO entry created.'));
    }

    public function edit(SeoMeta $seoMeta): View
    {
        return view('admin.seo.edit', compact('seoMeta'));
    }

    public function update(Request $request, SeoMeta $seoMeta): RedirectResponse
    {
        $data = $this->validated($request, $seoMeta->id);
        if ($request->hasFile('og_image')) {
            if ($seoMeta->og_image) {
                Storage::disk('public')->delete($seoMeta->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }
        $seoMeta->update($data);
        ActivityLog::record('seo.updated', $seoMeta->page_key, $seoMeta);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.seo.index')->with('success', __('SEO entry updated.'));
    }

    public function destroy(SeoMeta $seoMeta): RedirectResponse
    {
        if ($seoMeta->og_image) {
            Storage::disk('public')->delete($seoMeta->og_image);
        }
        ActivityLog::record('seo.deleted', $seoMeta->page_key, $seoMeta);
        $seoMeta->delete();
        Cache::forget('home_page_v3');

        return redirect()->route('admin.seo.index')->with('success', __('SEO entry deleted.'));
    }

    protected function validated(Request $request, ?int $ignoreId): array
    {
        return $request->validate([
            'page_key' => ['required', 'string', 'max:100', Rule::unique('seo_metas', 'page_key')->ignore($ignoreId)],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:1024'],
            'og_image' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}
