@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Mountains').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Mountains'), 'subtitle' => __('Summits, ridges, and alpine light across Africa.')])

<section class="site-gutter-x mx-auto max-w-7xl section-y-compact bg-white section-divider">
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($items as $mountain)
            <article
                class="card-depth img-zoom-parent overflow-hidden bg-surface"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <div class="aspect-[16/10] overflow-hidden bg-secondary/40">
                    @if($mountain->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image) }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                    @endif
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-primary">{{ $mountain->name }}</h2>
                    @if($mountain->elevation_m)<p class="text-sm text-accent">{{ $mountain->elevation_m }} m</p>@endif
                    <p class="mt-2 text-sm text-ink/75">{{ \Illuminate\Support\Str::limit($mountain->description, 160) }}</p>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center text-ink/60">{{ __('No mountains listed yet.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
