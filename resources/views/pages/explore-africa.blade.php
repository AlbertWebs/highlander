@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Explore Africa').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Explore Africa'), 'subtitle' => __('From coastlines to savannas—discover where we travel.')])

<section class="site-gutter-x mx-auto max-w-7xl section-y-compact bg-surface section-divider">
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($items as $dest)
            <article
                class="card-depth group img-zoom-parent overflow-hidden bg-surface"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <div class="aspect-[4/3] overflow-hidden">
                    @if($dest->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($dest->image) }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                    @endif
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-primary">{{ $dest->name }}</h2>
                    <p class="mt-2 text-sm text-ink/75">{{ \Illuminate\Support\Str::limit($dest->description, 180) }}</p>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center">{{ __('Destinations coming soon.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
