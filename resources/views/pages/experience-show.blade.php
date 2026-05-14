@extends('layouts.site')

@section('title', $pageTitle)

@push('meta')
    @if(filled($meta_description))
        <meta name="description" content="{{ $meta_description }}">
    @endif
    <meta property="og:title" content="{{ $meta_title }}">
    @if(filled($meta_description))
        <meta property="og:description" content="{{ $meta_description }}">
    @endif
    @if($tour->imageUrl())
        <meta property="og:image" content="{{ $tour->imageUrl() }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
    <link rel="canonical" href="{{ route('experiences.show', $tour) }}">
@endpush

@push('scripts')
    <script type="application/ld+json" class="seo-jsonld">@json($seoJsonLd)</script>
@endpush

@php
    $heroImage = $tour->imageUrl()
        ? $tour->imageUrl()
        : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80';
    $subtitleParts = [];
    if ($tour->duration_days) {
        $subtitleParts[] = trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]);
    }
    $heroSubtitle = count($subtitleParts) ? implode(' · ', $subtitleParts) : __('Guided safari itineraries tailored to your dates.');
    $tourSeo = $tourSeo ?? [];
@endphp

@section('content')
@include('partials.page-hero', [
    'title' => $tour->title,
    'subtitle' => $heroSubtitle,
    'image' => $heroImage,
    'kicker' => __('Experience'),
    'immersive' => true,
    'wide' => true,
])

<section class="relative z-10 mt-6 bg-surface pb-20 sm:mt-8 lg:mt-10" aria-label="{{ __('Safari experience') }}">
    <div class="site-gutter-x w-full max-w-none">
        <div class="w-full rounded-t-[1.25rem] border border-secondary/25 bg-gradient-to-b from-white to-surface/90 px-5 py-10 shadow-[0_-8px_40px_rgba(46,46,46,0.07)] sm:rounded-t-[1.75rem] sm:px-10 sm:py-12 lg:px-14">
            <div class="flex flex-col gap-4 border-b border-secondary/25 pb-8 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                <nav class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-ink/55 sm:text-sm" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ route('home') }}" class="rounded-md text-primary transition hover:bg-primary/5 hover:text-primary hover:underline">{{ __('Home') }}</a>
                    <span class="text-ink/30" aria-hidden="true">
                        <svg class="inline h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <a href="{{ route('safari') }}" class="rounded-md text-primary transition hover:bg-primary/5 hover:text-primary hover:underline">{{ __('Safari') }}</a>
                    <span class="text-ink/30" aria-hidden="true">
                        <svg class="inline h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <span class="max-w-[min(100%,20rem)] truncate text-ink/80 sm:max-w-[min(100%,28rem)]" title="{{ $tour->title }}">{{ $tour->title }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2">
                    <a
                        href="#plan-this-experience"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-primary/30 bg-primary/[0.06] px-3.5 py-2 text-xs font-semibold uppercase tracking-wide text-primary shadow-sm transition hover:bg-primary/[0.1]"
                    >{{ __('Plan') }}</a>
                    <a
                        href="{{ route('safari') }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-secondary/50 bg-white/90 px-3.5 py-2 text-xs font-semibold uppercase tracking-wide text-ink/80 shadow-sm transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary sm:text-[0.7rem]"
                    >
                        <svg class="h-3.5 w-3.5 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        {{ __('All safari styles') }}
                    </a>
                </div>
            </div>

            <div class="mt-10 lg:grid lg:grid-cols-12 lg:items-stretch lg:gap-10 xl:gap-14 2xl:gap-16">
                <div class="min-w-0 lg:col-span-8">
                    <article class="seo-experience-article">
                        @if($tour->featuredCardShowsVideo())
                            <div class="mb-10" data-aos="fade-up">
                                <div class="group relative aspect-video overflow-hidden rounded-card border border-primary/15 bg-secondary/20 shadow-depth ring-1 ring-primary/10">
                                    @include('partials.tour-featured-media', ['tour' => $tour])
                                </div>
                            </div>
                        @endif

                        @if(filled($tour->description))
                            <div class="mb-10 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8" data-aos="fade-up">
                                <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Itinerary snapshot') }}</h2>
                                <div class="prose prose-ink prose-headings:font-serif prose-site mt-4 max-w-none text-[1.0625rem] leading-[1.75] text-ink/90 sm:text-lg sm:leading-[1.7]">
                                    <p class="whitespace-pre-wrap">{{ $tour->description }}</p>
                                </div>
                            </div>
                        @endif

                        @include('partials.experience-seo-body', [
                            'tour' => $tour,
                            'tourSeo' => $tourSeo,
                            'zone' => 'upper',
                        ])

                        @if($tour->itineraryDays->isNotEmpty())
                            <div class="relative mt-14 border-t border-secondary/40 pt-12" aria-labelledby="itinerary-timeline-heading">
                                <h2 id="itinerary-timeline-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.65rem]" data-aos="fade-up">{{ __('Day-by-day timeline') }}</h2>
                                <p class="mt-2 max-w-2xl text-sm text-ink/60" data-aos="fade-up" data-aos-delay="40">{{ __('Follow the rhythm of the journey: each card is a chapter in the field.') }}</p>
                                <ol class="relative mt-10 space-y-0 pl-0 sm:pl-4">
                                    @foreach($tour->itineraryDays as $day)
                                        <li class="relative pb-12 pl-10 sm:pl-14 last:pb-0" data-aos="fade-up" data-aos-delay="{{ min(200, 50 * $loop->index) }}">
                                            <span class="absolute left-0 top-1.5 flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary/40 bg-white text-xs font-bold text-primary shadow-sm sm:left-1" aria-hidden="true">{{ $day->day_number }}</span>
                                            @if(! $loop->last)
                                                <span class="absolute left-[0.9rem] top-10 bottom-0 w-px bg-gradient-to-b from-primary/35 to-transparent sm:left-[1.35rem]" aria-hidden="true"></span>
                                            @endif
                                            <div class="overflow-hidden rounded-card border border-secondary/30 bg-white shadow-sm ring-1 ring-black/[0.02]">
                                                @if(filled($day->image))
                                                    <div class="img-zoom-parent aspect-[21/9] max-h-52 w-full overflow-hidden bg-secondary/30 sm:aspect-[3/1] sm:max-h-none">
                                                        <img
                                                            src="{{ $day->imageUrl() }}"
                                                            alt="{{ __('Day :num - :title - safari itinerary', ['num' => $day->day_number, 'title' => $day->title]) }}"
                                                            class="img-zoom-hover h-full w-full object-cover"
                                                            loading="lazy"
                                                            width="1200"
                                                            height="400"
                                                        >
                                                    </div>
                                                @endif
                                                <div class="p-5 sm:p-6">
                                                    <h3 class="text-lg font-semibold text-primary sm:text-xl">{{ __('Day') }} {{ $day->day_number }} - {{ $day->title }}</h3>
                                                    @if(filled($day->body))
                                                        <div class="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-ink/85">{{ $day->body }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endif

                        @include('partials.experience-seo-body', [
                            'tour' => $tour,
                            'tourSeo' => $tourSeo,
                            'zone' => 'lower',
                            'testimonials' => $testimonials ?? collect(),
                            'relatedTours' => $relatedTours ?? collect(),
                        ])
                    </article>
                </div>

                <aside class="mt-10 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Trip details and related experiences') }}">
                    @include('partials.experience-sidebar', ['tour' => $tour, 'relatedTours' => $relatedTours])
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
