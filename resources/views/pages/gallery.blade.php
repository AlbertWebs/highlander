@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Gallery').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.gallery-hero', ['subtitle' => __('Moments from the road — filter by adventure type.')])

<section class="bg-[#F8F8F8] section-divider pb-20 pt-10 sm:pb-24 sm:pt-12" x-data="{ filter: 'all' }">
    <div class="site-gutter-x mx-auto max-w-7xl">
        {{-- Category filter bar --}}
        <div class="mb-10 flex justify-center sm:mb-12" data-aos="fade-up" data-aos-duration="700">
            <div class="inline-flex max-w-full flex-wrap items-center justify-center gap-1 rounded-xl border border-neutral-300/90 bg-white px-2 py-2 shadow-sm sm:gap-0 sm:px-3 sm:py-2.5">
                <button
                    type="button"
                    @click="filter = 'all'"
                    :class="filter === 'all' ? 'bg-primary/10 text-primary ring-1 ring-primary/25' : 'text-ink/70 hover:bg-neutral-50 hover:text-ink'"
                    class="rounded-lg px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.12em] transition duration-200 sm:px-4 sm:text-xs"
                >{{ __('All') }}</button>
                @foreach($categories as $cat)
                    <button
                        type="button"
                        @click="filter = @js($cat->slug)"
                        :class="filter === @js($cat->slug) ? 'bg-primary/10 text-primary ring-1 ring-primary/25' : 'text-ink/70 hover:bg-neutral-50 hover:text-ink'"
                        class="rounded-lg px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.1em] transition duration-200 sm:px-4 sm:text-xs"
                    >{{ $cat->name }}</button>
                @endforeach
            </div>
        </div>

        @if($items->isEmpty())
            <p class="py-16 text-center text-ink/60">{{ __('Gallery images coming soon.') }}</p>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3 lg:gap-8">
                @foreach($items as $g)
                    @php
                        $catSlug = $g->category?->slug ?? '';
                    @endphp
                    <figure
                        x-show="filter === 'all' || filter === @js($catSlug)"
                        x-transition
                        class="img-zoom-parent overflow-hidden bg-white shadow-sm ring-1 ring-black/[0.04] transition-shadow duration-300 hover:shadow-md"
                        data-aos="fade-up"
                        data-aos-duration="700"
                        data-aos-delay="{{ min(350, 40 * ($loop->index % 12)) }}"
                    >
                        <div class="aspect-[3/2] overflow-hidden bg-neutral-100">
                            <img
                                src="{{ $g->url }}"
                                alt="{{ $g->alt ?? $g->title ?? __('Gallery image') }}"
                                class="img-zoom-hover h-full w-full object-cover"
                                loading="lazy"
                                width="800"
                                height="533"
                            >
                        </div>
                        @if($g->title || $g->category)
                            <figcaption class="border-t border-neutral-100 px-4 py-3">
                                @if($g->category)
                                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-primary">{{ $g->category->name }}</p>
                                @endif
                                @if($g->title)
                                    <p class="mt-1 text-sm font-medium text-ink">{{ $g->title }}</p>
                                @endif
                            </figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
