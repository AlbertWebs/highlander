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
    @if($tour->image)
        <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->image) }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endpush

@php
    $heroImage = $tour->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($tour->image)
        : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80';
    $subtitleParts = [];
    if ($tour->price) {
        $subtitleParts[] = __('From').' $'.number_format((float) $tour->price, 0);
    }
    if ($tour->duration_days) {
        $subtitleParts[] = trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]);
    }
    $heroSubtitle = count($subtitleParts) ? implode(' · ', $subtitleParts) : null;
@endphp

@section('content')
@include('partials.page-hero', ['title' => $tour->title, 'subtitle' => $heroSubtitle, 'image' => $heroImage])

@if($tour->featuredCardShowsVideo())
    <section class="site-gutter-x mx-auto max-w-5xl bg-white pb-10 pt-4 section-divider">
        <div class="group relative aspect-video overflow-hidden rounded-card border border-primary/15 bg-secondary/20 shadow-depth ring-1 ring-primary/10">
            @include('partials.tour-featured-media', ['tour' => $tour])
        </div>
    </section>
@endif

<section class="site-gutter-x mx-auto max-w-3xl section-y-compact bg-surface pb-20 pt-6 sm:pt-10">
    @if(filled($tour->description))
        <div class="prose prose-ink max-w-none text-base leading-relaxed text-ink/90">
            <p class="whitespace-pre-wrap">{{ $tour->description }}</p>
        </div>
    @endif

    @if($tour->itineraryDays->isNotEmpty())
        <div class="mt-12 border-t border-secondary/40 pt-10">
            <h2 class="font-serif text-2xl font-semibold text-primary">{{ __('Itinerary') }}</h2>
            <ol class="mt-6 space-y-5">
                @foreach($tour->itineraryDays as $day)
                    <li class="card-depth overflow-hidden bg-white">
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

    <div class="mt-12 flex flex-col gap-3 border-t border-secondary/40 pt-10 sm:flex-row sm:flex-wrap">
        <a
            href="{{ route('plan-my-safari', ['tour' => $tour->slug]) }}"
            class="btn-primary flex-1 bg-gradient-to-r from-primary via-primary to-accent px-6 py-3 hover:brightness-110 sm:min-w-[12rem] sm:flex-none"
        >{{ __('Plan This Safari') }}</a>
        <a
            href="{{ route('home') }}#featured-experiences-heading"
            class="btn-secondary flex-1 px-6 py-3 sm:min-w-[12rem] sm:flex-none"
        >{{ __('More experiences') }}</a>
    </div>
</section>
@endsection
