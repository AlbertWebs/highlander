@extends('layouts.site')

@section('title', $article->meta_title ?? $article->title.' — '.config('app.name'))

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
<section class="bg-[#F8F8F8] pt-28 section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl py-12 sm:py-14">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 xl:gap-14">
            <article class="min-w-0 lg:col-span-8">
                <p class="text-sm font-semibold text-accent" data-aos="fade-up" data-aos-duration="800">{{ optional($article->published_at)->format('F j, Y') }}</p>
                <h1 class="mt-2 text-4xl font-bold text-primary" data-aos="fade-up" data-aos-duration="850" data-aos-delay="60">{{ $article->title }}</h1>
                @if($article->featured_image)
                    <div class="mt-8 aspect-video overflow-hidden rounded-2xl bg-secondary/40" data-aos="zoom-in" data-aos-duration="850" data-aos-delay="100">
                        <img src="{{ $article->featuredImageUrl() }}" alt="" class="h-full w-full object-cover" loading="lazy">
                    </div>
                @endif
                <div class="prose prose-lg mt-10 max-w-none text-ink prose-headings:text-primary prose-a:text-primary prose-img:rounded-xl" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
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
