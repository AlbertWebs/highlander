@extends('layouts.site')

@section('title', $article->meta_title ?? $article->title.' - '.config('app.name'))

@push('meta')
    @if($article->meta_description)
        <meta name="description" content="{{ $article->meta_description }}">
    @endif
    <meta name="keywords" content="Highlanders Nature Trails, African safari travel blog, wildlife safari stories, East Africa travel guide, nature expeditions, safari planning tips, responsible tourism Africa">
    <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}">
    @if($article->meta_description)
        <meta property="og:description" content="{{ $article->meta_description }}">
    @endif
    @if($article->featured_image)
        <meta property="og:image" content="{{ $article->featuredImageUrl() }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endpush

@section('content')
@php
    $heroBgUrl = $article->featuredImageUrl()
        ?? 'https://images.unsplash.com/photo-1632854385904-e3b76814e478?auto=format&fit=crop&w=2000&q=80';
@endphp

{{-- Hero: same breadcrumb pattern as plan-my-safari --}}
<section class="relative isolate flex min-h-[36vh] flex-col justify-end overflow-hidden pt-32 pb-10 sm:min-h-[40vh] sm:pb-12" aria-labelledby="article-hero-heading">
    <div
        class="absolute inset-0 z-0 bg-cover bg-center"
        style="background-image:
            linear-gradient(to top, rgba(10,12,12,0.82) 0%, rgba(10,12,12,0.35) 38%, rgba(10,12,12,0) 55%),
            linear-gradient(165deg, rgba(10,12,12,0.88) 0%, rgba(10,12,12,0.55) 45%, rgba(46,90,60,0.32) 100%),
            url({{ json_encode($heroBgUrl) }});"
    ></div>
    <div class="site-gutter-x relative z-10 w-full text-white" data-aos="fade-up" data-aos-duration="900">
        <nav class="mb-6 text-xs font-medium text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-white/80 transition hover:text-white">{{ __('Home') }}</a></li>
                <li aria-hidden="true" class="text-white/40">/</li>
                <li><a href="{{ route('articles') }}" class="text-white/80 transition hover:text-white">{{ __('Articles') }}</a></li>
                <li aria-hidden="true" class="text-white/40">/</li>
                <li class="max-w-[min(100%,42rem)] text-white/95" aria-current="page">
                    <span class="line-clamp-2 font-medium">{{ $article->title }}</span>
                </li>
            </ol>
        </nav>
        @if($article->published_at)
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-white/80">{{ optional($article->published_at)->format('F j, Y') }}</p>
        @endif
        <h1 id="article-hero-heading" class="mt-3 max-w-4xl font-serif text-4xl font-medium leading-[1.12] tracking-tight text-white sm:text-5xl lg:text-[3rem]">{{ $article->title }}</h1>
    </div>
</section>

<section class="bg-[#F8F8F8] section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl py-12 sm:py-14">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 xl:gap-14">
            <article class="min-w-0 lg:col-span-8">
                <div class="prose prose-lg prose-site max-w-none text-[1.0625rem] leading-[1.75] text-ink sm:text-lg sm:leading-[1.7]" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
                    {!! $article->body !!}
                </div>
                <a href="{{ route('articles') }}" class="mt-12 inline-flex text-sm font-semibold text-primary transition hover:underline" data-aos="fade-up" data-aos-duration="800">← {{ __('Back to articles') }}</a>
            </article>
            <div class="lg:col-span-4">
                @include('partials.articles-sidebar', ['recentArticles' => $sidebarArticles])
            </div>
        </div>
    </div>
</section>
@endsection
