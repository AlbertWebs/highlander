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
                    <span class="max-w-[min(100%,20rem)] truncate text-ink/80 sm:max-w-[min(100%,28rem)]" title="{{ $safariExperience->title }}">{{ $safariExperience->title }}</span>
                </nav>
                <div class="flex flex-wrap gap-2">
                    <a
                        href="#plan-this-safari-style"
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
                    @if(filled($safariExperience->description))
                        <div class="mb-10 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8" data-aos="fade-up">
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Style snapshot') }}</h2>
                            <div class="prose prose-ink prose-site mt-4 max-w-none text-[1.0625rem] leading-[1.75] text-ink/90 sm:text-lg sm:leading-[1.7]">
                                <p class="whitespace-pre-wrap">{{ $safariExperience->description }}</p>
                            </div>
                        </div>
                    @endif

                    @include('partials.safari-style-seo-body', [
                        'safariExperience' => $safariExperience,
                        'safariSeo' => $safariSeo,
                        'testimonials' => $testimonials ?? collect(),
                        'relatedTours' => $relatedTours ?? collect(),
                    ])
                </div>

                <aside class="mt-10 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Safari style and related trips') }}">
                    @include('partials.safari-experience-sidebar', ['safariExperience' => $safariExperience, 'relatedTours' => $relatedTours])
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
