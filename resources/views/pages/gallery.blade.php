@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Gallery').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.gallery-hero', ['subtitle' => __('Moments from the road — filter by adventure type.')])

<section
    class="bg-[#F8F8F8] section-divider pb-20 pt-10 sm:pb-24 sm:pt-12"
    x-data="{
        filter: 'all',
        lightboxOpen: false,
        lightboxSrc: '',
        lightboxAlt: '',
        lightboxCaption: '',
        openLightbox(src, alt, caption) {
            this.lightboxSrc = src;
            this.lightboxAlt = alt || '';
            this.lightboxCaption = caption || '';
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            this.lightboxSrc = '';
            document.body.style.overflow = '';
        },
    }"
    @keydown.escape.window="lightboxOpen && closeLightbox()"
>
    <div class="site-gutter-x mx-auto max-w-[1600px]">
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
            <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4 lg:gap-5 xl:gap-6" role="list">
                @foreach($items as $g)
                    @php
                        $catSlug = $g->category?->slug ?? '';
                        $imgAlt = $g->alt ?? $g->title ?? __('Gallery image');
                        $caption = trim(collect([$g->category?->name, $g->title])->filter()->implode(' — '));
                    @endphp
                    <li
                        x-show="filter === 'all' || filter === @js($catSlug)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-[0.98]"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="min-w-0"
                        data-aos="fade-up"
                        data-aos-duration="700"
                        data-aos-delay="{{ min(320, 35 * ($loop->index % 16)) }}"
                    >
                        <figure class="card-depth group flex h-full flex-col overflow-hidden rounded-card">
                            <button
                                type="button"
                                class="img-zoom-parent relative block w-full overflow-hidden text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                @click="openLightbox(@js($g->url), @js($imgAlt), @js($caption))"
                                aria-label="{{ __('Open image larger') }}: {{ $g->title ?? $imgAlt }}"
                            >
                                <span class="relative block aspect-[4/3] overflow-hidden bg-neutral-200">
                                    <img
                                        src="{{ $g->url }}"
                                        alt="{{ $imgAlt }}"
                                        class="img-zoom-hover h-full w-full object-cover"
                                        loading="lazy"
                                        decoding="async"
                                        width="640"
                                        height="480"
                                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 25vw"
                                    >
                                    <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent opacity-0 transition duration-300 group-hover:opacity-100" aria-hidden="true"></span>
                                    <span class="pointer-events-none absolute bottom-3 left-3 right-3 flex items-end justify-between gap-2 opacity-0 transition duration-300 group-hover:opacity-100" aria-hidden="true">
                                        <span class="line-clamp-2 text-left text-xs font-semibold uppercase tracking-[0.08em] text-white drop-shadow-sm">
                                            {{ $g->title ?: __('View') }}
                                        </span>
                                        <span class="inline-flex shrink-0 items-center justify-center rounded-full bg-white/95 p-2 text-primary shadow-md ring-1 ring-black/5">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                            </svg>
                                        </span>
                                    </span>
                                </span>
                            </button>
                            @if($g->title || $g->category)
                                <figcaption class="border-t border-secondary/25 bg-white px-3 py-2.5 sm:px-4 sm:py-3">
                                    @if($g->category)
                                        <p class="text-[0.6rem] font-semibold uppercase tracking-[0.14em] text-accent">{{ $g->category->name }}</p>
                                    @endif
                                    @if($g->title)
                                        <p class="mt-0.5 line-clamp-2 text-sm font-medium leading-snug text-ink">{{ $g->title }}</p>
                                    @endif
                                </figcaption>
                            @endif
                        </figure>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <template x-teleport="body">
        <div
            x-show="lightboxOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 pt-14 sm:p-8"
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('Image') }}"
        >
            <div
                class="absolute inset-0 z-0 bg-ink/88 backdrop-blur-md"
                @click="closeLightbox()"
            ></div>
            <button
                type="button"
                class="fixed right-4 top-4 z-[110] inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white ring-1 ring-white/30 transition hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:right-6 sm:top-6"
                @click="closeLightbox()"
                aria-label="{{ __('Close') }}"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="relative z-10 flex w-full max-w-6xl flex-col items-center">
                <div class="w-full overflow-hidden rounded-2xl bg-black/35 shadow-2xl ring-1 ring-white/15">
                    <img
                        :src="lightboxSrc"
                        :alt="lightboxAlt"
                        class="mx-auto max-h-[min(76vh,820px)] w-auto max-w-full object-contain"
                        width="1600"
                        height="1200"
                        decoding="async"
                    >
                </div>
                <p
                    x-show="lightboxCaption"
                    x-text="lightboxCaption"
                    class="mt-4 max-w-2xl px-2 text-center text-sm leading-relaxed text-white/90"
                ></p>
            </div>
        </div>
    </template>
</section>
@endsection
