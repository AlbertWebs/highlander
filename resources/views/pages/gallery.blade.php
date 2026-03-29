@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Gallery').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Gallery'), 'subtitle' => __('Moments from the road.')])

<section class="site-gutter-x mx-auto max-w-7xl section-y-compact bg-surface section-divider">
    <div class="columns-1 gap-4 sm:columns-2 lg:columns-3">
        @forelse($items as $g)
            <figure
                class="img-zoom-parent group mb-4 break-inside-avoid overflow-hidden rounded-card bg-secondary/30 shadow-depth transition-[box-shadow] duration-300 ease-out hover:shadow-depth-hover"
                data-aos="zoom-in"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 80 * $loop->index) }}"
            >
                <img src="{{ $g->url }}" alt="{{ $g->alt ?? $g->title }}" class="img-zoom-hover w-full object-cover" loading="lazy">
                @if($g->title)<figcaption class="p-3 text-sm text-ink/70">{{ $g->title }}</figcaption>@endif
            </figure>
        @empty
            <p class="col-span-full text-center">{{ __('Gallery images coming soon.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
