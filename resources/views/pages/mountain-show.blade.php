@extends('layouts.site')

@section('title', $pageTitle)

@push('meta')
    <meta name="description" content="{{ $meta_description }}">
    <meta property="og:title" content="{{ $meta_title }}">
    <meta property="og:description" content="{{ $meta_description }}">
    @if($mountain->image)
        <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image) }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endpush

@php
    $heroImage = $mountain->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image)
        : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=2000&q=80';
    $heroSubtitle = $mountain->elevation_m
        ? __('Summit trekking, acclimatisation, and routes tailored to your dates.')
        : __('High-altitude trekking and summit routes across East Africa.');
@endphp

@section('content')
@include('partials.page-hero', [
    'title' => $mountain->name,
    'subtitle' => $heroSubtitle,
    'image' => $heroImage,
    'kicker' => __('Mountain'),
    'immersive' => true,
    'wide' => true,
])

{{-- Content panel below hero — spaced from header image (surface gap) --}}
<section class="relative z-10 mt-6 bg-surface pb-20 sm:mt-8 lg:mt-10" aria-labelledby="mountain-overview-heading">
    <div class="site-gutter-x w-full max-w-none">
        <div class="w-full rounded-t-[1.25rem] border border-secondary/25 bg-gradient-to-b from-white to-surface/90 px-5 py-10 shadow-[0_-8px_40px_rgba(46,46,46,0.07)] sm:rounded-t-[1.75rem] sm:px-10 sm:py-12 lg:px-14">
            <div class="flex flex-col gap-4 border-b border-secondary/25 pb-8 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                <nav class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium text-ink/55 sm:text-sm" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ route('home') }}" class="rounded-md text-primary transition hover:bg-primary/5 hover:text-primary hover:underline">{{ __('Home') }}</a>
                    <span class="text-ink/30" aria-hidden="true">
                        <svg class="inline h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <a href="{{ route('mountains') }}" class="rounded-md text-primary transition hover:bg-primary/5 hover:text-primary hover:underline">{{ __('Mountains') }}</a>
                    <span class="text-ink/30" aria-hidden="true">
                        <svg class="inline h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    <span class="max-w-[min(100%,20rem)] truncate text-ink/80 sm:max-w-[min(100%,28rem)]" title="{{ $mountain->name }}">{{ $mountain->name }}</span>
                </nav>
                <a
                    href="{{ route('mountains') }}"
                    class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-secondary/50 bg-white/90 px-3.5 py-2 text-xs font-semibold uppercase tracking-wide text-ink/80 shadow-sm transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary sm:self-auto sm:text-[0.7rem]"
                >
                    <svg class="h-3.5 w-3.5 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('All mountains') }}
                </a>
            </div>

            {{-- items-stretch so the aside column is as tall as the main column; required for sticky sidebar --}}
            <div class="mt-10 lg:grid lg:grid-cols-12 lg:items-stretch lg:gap-10 xl:gap-14 2xl:gap-16">
                <div class="min-w-0 lg:col-span-8">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-secondary/20 pb-4">
                        <div>
                            <h2 id="mountain-overview-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Overview') }}</h2>
                            <p class="mt-1 text-sm text-ink/55">{{ __('Route notes, terrain, and what to expect.') }}</p>
                        </div>
                        <a href="#plan-this-mountain" class="inline-flex shrink-0 text-sm font-semibold text-primary underline decoration-primary/35 underline-offset-2 transition hover:decoration-primary">
                            {{ __('Jump to planning') }}
                        </a>
                    </div>

                    @if(filled($mountain->description))
                        <div class="mt-6 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8">
                            <div class="prose prose-ink prose-headings:font-serif prose-headings:tracking-tight prose-headings:text-primary prose-h2:text-2xl prose-h3:text-xl prose-a:text-primary prose-a:no-underline hover:prose-a:underline prose-site max-w-none text-[1.0625rem] leading-[1.75] text-ink/90 sm:text-lg sm:leading-[1.7]">
                                {!! $mountain->description !!}
                            </div>
                        </div>
                    @else
                        <div class="mt-6 rounded-card border border-dashed border-secondary/50 bg-surface/80 p-8 text-center">
                            <p class="text-lg leading-relaxed text-ink/65">{{ __('We are preparing a full description for this peak. Enquire below and we will tailor a trekking or combined safari plan for you.') }}</p>
                        </div>
                    @endif
                </div>

                <aside class="mt-10 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Mountain details and related trips') }}">
                    @include('partials.mountain-sidebar', ['mountain' => $mountain, 'relatedTours' => $relatedTours])
                </aside>
            </div>

            <div id="plan-this-mountain" class="mt-14 scroll-mt-28 sm:mt-16">
                <div class="rounded-card border border-secondary/40 bg-gradient-to-br from-white via-surface/80 to-tint-green/25 px-6 py-9 shadow-card sm:px-10 sm:py-11">
                    <p class="text-center text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Next step') }}</p>
                    <h2 class="mt-2 text-center font-serif text-xl font-semibold text-ink sm:text-2xl">{{ __('Plan or ask about this mountain') }}</h2>
                    <p class="mx-auto mt-2 max-w-lg text-center text-sm leading-relaxed text-ink/60">{{ __('We will match dates, fitness level, and any add-on safaris so the trip feels right for you.') }}</p>
                    <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
                        <a
                            href="{{ route('plan-my-safari', ['mountain' => $mountain->slug]) }}"
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
                            href="{{ route('mountains') }}"
                            class="btn-outline order-3 flex min-h-[3rem] items-center justify-center gap-2 px-6 py-3.5 sm:order-none"
                        >
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            {{ __('All mountains') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
