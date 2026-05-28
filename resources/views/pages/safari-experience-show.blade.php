@extends('layouts.site')

@section('title', $pageTitle)

@push('meta')
    <meta name="description" content="{{ $meta_description }}">
    <meta property="og:title" content="{{ $meta_title }}">
    <meta property="og:description" content="{{ $meta_description }}">
    <meta property="og:image" content="{{ $safariExperience->cardImageUrl() }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ route('safari.show', $safariExperience) }}">
@endpush

@push('scripts')
    <script type="application/ld+json" class="seo-jsonld">@json($seoJsonLd)</script>
@endpush

@php
    $heroImage = $safariExperience->cardImageUrl();
    $heroSubtitle = filled($safariExperience->duration)
        ? $safariExperience->duration
        : __('Wildlife, reserves, and pacing tailored to your dates.');
    $safariSeo = $safariSeo ?? [];
@endphp

@section('content')
@include('partials.page-hero', [
    'title' => $safariExperience->title,
    'subtitle' => $heroSubtitle,
    'image' => $heroImage,
    'kicker' => __('Safari'),
    'immersive' => true,
    'wide' => true,
])

<section class="relative z-10 mt-6 bg-surface pb-20 sm:mt-8 lg:mt-10" aria-label="{{ __('Safari style') }}">
    <div class="site-gutter-x w-full max-w-none">
        <div class="w-full rounded-t-[1.25rem] border border-secondary/25 bg-gradient-to-b from-white to-surface/90 px-5 py-10 shadow-[0_-8px_40px_rgba(46,46,46,0.07)] sm:rounded-t-[1.75rem] sm:px-10 sm:py-12 lg:px-14">
            <div class="lg:grid lg:grid-cols-12 lg:gap-10 xl:gap-14 2xl:gap-16">
                <div class="min-w-0 lg:col-span-8">
                    @if(filled($safariExperience->description))
                        <div class="rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8">
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Introduction') }}</h2>
                            <div class="prose prose-ink prose-site mt-4 max-w-none text-[1.0625rem] leading-[1.75] text-ink/90 sm:text-lg sm:leading-[1.7]">
                                <p class="whitespace-pre-wrap">{{ $safariExperience->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Itineraries for this safari') }}</h2>
                            <a href="{{ route('safari') }}" class="text-xs font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('All safari styles') }}</a>
                        </div>

                        @if(($relatedTours ?? collect())->isNotEmpty())
                            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($relatedTours as $tour)
                                    <a href="{{ route('experiences.show', $tour) }}" class="group rounded-xl border border-secondary/40 bg-surface/40 p-4 transition hover:border-primary/35 hover:bg-white">
                                        <p class="text-sm font-semibold text-ink group-hover:text-primary">{{ $tour->title }}</p>
                                        @if($tour->duration_days)
                                            <p class="mt-1 text-xs text-ink/55">{{ trans_choice(':days day|:days days', $tour->duration_days, ['days' => $tour->duration_days]) }}</p>
                                        @endif
                                        <p class="mt-2 text-xs font-semibold text-primary">{{ __('View itinerary') }} &rarr;</p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-sm text-ink/65">{{ __('No itineraries have been added for this safari yet.') }}</p>
                        @endif
                    </div>
                </div>

                <aside class="mt-8 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Safari style sidebar') }}">
                    @include('partials.safari-experience-sidebar', ['safariExperience' => $safariExperience, 'relatedTours' => $relatedTours])
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
