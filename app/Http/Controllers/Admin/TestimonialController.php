<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->trim();
        $testimonials = Testimonial::query()
            ->when($q, fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', compact('testimonials', 'q'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['show_on_about'] = $request->boolean('show_on_about');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }
        $t = Testimonial::query()->create($data);
        ActivityLog::record('testimonial.created', $t->name, $t);
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');

        return redirect()->route('admin.testimonials.index')->with('success', __('Testimonial created.'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['show_on_about'] = $request->boolean('show_on_about');
        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }
        $testimonial->update($data);
        ActivityLog::record('testimonial.updated', $testimonial->name, $testimonial);
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');

        return redirect()->route('admin.testimonials.index')->with('success', __('Testimonial updated.'));
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }
        ActivityLog::record('testimonial.deleted', $testimonial->name, $testimonial);
        $testimonial->delete();
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');

        return redirect()->route('admin.testimonials.index')->with('success', __('Testimonial deleted.'));
    }

    public function toggle(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');

        return back()->with('success', __('Status updated.'));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'safari_type' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
