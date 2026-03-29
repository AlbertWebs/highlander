<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Support\SlugHelper;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $articles = Article::query()
            ->when($q, fn ($query) => $query->where('title', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', compact('articles', 'q'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = SlugHelper::unique(Article::class, 'slug', $data['title']);
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }
        $data['published_at'] = ! empty($data['published_at']) ? Carbon::parse($data['published_at']) : null;
        $article = Article::query()->create($data);
        ActivityLog::record('article.created', $article->title, $article);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.articles.index')->with('success', __('Article created.'));
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('title') && $request->string('title') !== $article->title) {
            $data['slug'] = SlugHelper::unique(Article::class, 'slug', $data['title'], $article->id);
        }
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }
        if (array_key_exists('published_at', $data)) {
            $data['published_at'] = ! empty($data['published_at']) ? Carbon::parse($data['published_at']) : null;
        }
        $article->update($data);
        ActivityLog::record('article.updated', $article->title, $article);
        Cache::forget('home_page_v3');

        return redirect()->route('admin.articles.index')->with('success', __('Article updated.'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        ActivityLog::record('article.deleted', $article->title, $article);
        $article->delete();
        Cache::forget('home_page_v3');

        return redirect()->route('admin.articles.index')->with('success', __('Article deleted.'));
    }

    public function toggle(Article $article): RedirectResponse
    {
        $article->update(['is_active' => ! $article->is_active]);
        Cache::forget('home_page_v3');

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
