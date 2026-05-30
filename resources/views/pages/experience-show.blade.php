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
@endpush

@php
    $heroImage = $tour->imageUrl()
        ? $tour->imageUrl()
        : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80';
    $subtitleParts = [];
    if ($tour->price) {
        $subtitleParts[] = __('From').' $'.number_format((float) $tour->price, 0);
    }
    if ($tour->duration_days) {
        $subtitleParts[] = trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]);
    }
    $heroSubtitle = count($subtitleParts) ? implode(' · ', $subtitleParts) : __('Guided safari itineraries tailored to your dates.');
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

<section class="relative z-10 mt-6 bg-surface pb-20 sm:mt-8 lg:mt-10" aria-labelledby="experience-overview-heading">
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
                <a
                    href="{{ route('safari') }}"
                    class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-secondary/50 bg-white/90 px-3.5 py-2 text-xs font-semibold uppercase tracking-wide text-ink/80 shadow-sm transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary sm:self-auto sm:text-[0.7rem]"
                >
                    <svg class="h-3.5 w-3.5 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('All safari styles') }}
                </a>
            </div>

            <div class="mt-10 lg:grid lg:grid-cols-12 lg:items-stretch lg:gap-10 xl:gap-14 2xl:gap-16">
                <div class="min-w-0 lg:col-span-8">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-secondary/20 pb-4">
                        <div>
                            <h2 id="experience-overview-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Overview') }}</h2>
                            <p class="mt-1 text-sm text-ink/55">{{ __('What to expect, pacing, and how we tailor this trip.') }}</p>
                        </div>
                        <a href="#plan-this-experience" class="inline-flex shrink-0 text-sm font-semibold text-primary underline decoration-primary/35 underline-offset-2 transition hover:decoration-primary">
                            {{ __('Jump to planning') }}
                        </a>
                    </div>

                    @if($tour->featuredCardShowsVideo())
                        <div class="mt-6">
                            <div class="group relative aspect-video overflow-hidden rounded-card border border-primary/15 bg-secondary/20 shadow-depth ring-1 ring-primary/10">
                                @include('partials.tour-featured-media', ['tour' => $tour])
                            </div>
                        </div>
                    @endif

                    @if(filled($tour->description))
                        <div class="mt-6 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8">
                            <div class="prose prose-ink prose-headings:font-serif prose-headings:tracking-tight prose-headings:text-primary prose-h2:text-2xl prose-h3:text-xl prose-a:text-primary prose-a:no-underline hover:prose-a:underline prose-site max-w-none text-[1.0625rem] leading-[1.75] text-ink/90 sm:text-lg sm:leading-[1.7]">
                                <p class="whitespace-pre-wrap">{{ $tour->description }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 rounded-card border border-dashed border-secondary/50 bg-surface/80 p-8 text-center">
                            <p class="text-lg leading-relaxed text-ink/65">{{ __('We are preparing a full description for this itinerary. Enquire below and we will tailor dates and pacing for your group.') }}</p>
                        </div>
                    @endif

                    @if($tour->itineraryDays->isNotEmpty())
                        <div class="mt-12 border-t border-secondary/40 pt-10">
                            <h2 class="font-serif text-2xl font-semibold text-primary">{{ __('Itinerary') }}</h2>
                            <ol class="mt-6 space-y-5">
                                @foreach($tour->itineraryDays as $day)
                                    <li class="overflow-hidden rounded-card border border-secondary/30 bg-white shadow-sm">
                                        @if(filled($day->image))
                                            <div class="img-zoom-parent aspect-[21/9] max-h-52 w-full overflow-hidden bg-secondary/30 sm:aspect-[3/1] sm:max-h-none">
                                                <img src="{{ $day->imageUrl() }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                                            </div>
                                        @endif
                                        <div class="p-5 sm:p-6">
                                            <p class="font-semibold text-primary">{{ __('Day') }} {{ $day->day_number }} — {{ $day->title }}</p>
                                            @if(filled($day->body))
                                                <p class="mt-3 whitespace-pre-wrap text-sm leading-relaxed text-ink/85">{{ $day->body }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>

                <aside class="mt-10 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Trip details and related experiences') }}">
                    @include('partials.experience-sidebar', ['tour' => $tour, 'relatedTours' => $relatedTours])
                </aside>
            </div>

            <div id="plan-this-experience" class="mt-14 scroll-mt-28 sm:mt-16">
                <div class="rounded-card border border-secondary/40 bg-gradient-to-br from-white via-surface/80 to-tint-green/25 px-6 py-9 shadow-card sm:px-10 sm:py-11">
                    <p class="text-center text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Next step') }}</p>
                    <h2 class="mt-2 text-center font-serif text-xl font-semibold text-ink sm:text-2xl">{{ __('Plan or ask about this experience') }}</h2>
                    <p class="mx-auto mt-2 max-w-lg text-center text-sm leading-relaxed text-ink/60">{{ __('We will match dates, group size, and add-ons so the trip feels right for you.') }}</p>
                    <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
                        <a
                            href="{{ route('plan-my-safari', ['tour' => $tour->slug]) }}"
                            class="btn-primary group order-1 flex min-h-[3rem] items-center justify-center gap-2 bg-gradient-to-r from-primary via-primary to-accent px-6 py-3.5 hover:brightness-110 sm:order-none"
                        >
                            <svg class="h-4 w-4 shrink-0 opacity-90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('Plan this safari') }}
                        </a>
                        <a
                            href="{{ route('contact') }}"
                            class="btn-secondary order-2 flex min-h-[3rem] items-center justify-center gap-2 px-6 py-3.5 sm:order-none"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ __('Ask a question') }}
                        </a>
                        <a
                            href="{{ route('home') }}#featured-experiences-heading"
                            class="btn-outline order-3 flex min-h-[3rem] items-center justify-center gap-2 px-6 py-3.5 sm:order-none"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            {{ __('More experiences') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
