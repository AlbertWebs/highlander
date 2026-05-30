@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Articles').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.articles-hero', ['subtitle' => __('Stories from the field — travel notes, wildlife, and life on safari.')])

<section class="bg-[#F8F8F8] section-divider pb-20 pt-10 sm:pb-24 sm:pt-12">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 xl:gap-14">
            <div class="min-w-0 lg:col-span-8">
                @if($items->isEmpty())
                    <div class="rounded-card border border-secondary/30 bg-white px-8 py-16 text-center shadow-sm" data-aos="fade-up" data-aos-duration="700">
                        <p class="font-serif text-2xl text-primary">{{ __('Journal coming soon') }}</p>
                        <p class="mx-auto mt-3 max-w-md text-ink/65">{{ __('We are preparing field notes and stories. Check back shortly.') }}</p>
                    </div>
                @else
                    <div class="space-y-8 sm:space-y-10">
                        @foreach($items as $article)
                            <article
                                class="card-depth grid gap-6 overflow-hidden p-5 sm:gap-8 sm:p-6 md:grid-cols-3 md:items-center"
                                data-aos="fade-up"
                                data-aos-duration="700"
                                data-aos-delay="{{ min(350, 60 * $loop->index) }}"
                            >
                                <a href="{{ route('articles.show', $article) }}" class="img-zoom-parent relative block aspect-[3/2] shrink-0 overflow-hidden rounded-card bg-secondary/35 md:col-span-1 md:aspect-[4/3]">
                                    @if($article->featured_image)
                                        <img
                                            src="{{ $article->featuredImageUrl() }}"
                                            alt=""
                                            class="img-zoom-hover h-full w-full object-cover"
                                            loading="lazy"
                                            width="800"
                                            height="533"
                                        >
                                    @else
                                        <div class="flex h-full min-h-[10rem] items-center justify-center bg-gradient-to-br from-secondary/25 to-primary/[0.07] md:min-h-full">
                                            <span class="font-serif text-2xl italic text-primary/25" aria-hidden="true">{{ __('Journal') }}</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex flex-col md:col-span-2">
                                    <time datetime="{{ optional($article->published_at)->toIso8601String() }}" class="text-[0.65rem] font-semibold uppercase tracking-[0.12em] text-accent">
                                        {{ optional($article->published_at)->format('M j, Y') }}
                                    </time>
                                    <h2 class="mt-2 font-serif text-2xl font-semibold leading-snug text-primary sm:text-[1.6rem]">
                                        <a href="{{ route('articles.show', $article) }}" class="transition hover:text-primary/85 hover:underline">
                                            {{ $article->title }}
                                        </a>
                                    </h2>
                                    <p class="mt-3 line-clamp-4 text-base leading-relaxed text-ink/75">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->body), 280) }}
                                    </p>
                                    <a href="{{ route('articles.show', $article) }}" class="btn-secondary mt-5 self-start">
                                        {{ __('Read article') }}
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if($items->hasPages())
                        <div class="mt-14 flex justify-center" data-aos="fade-up" data-aos-duration="600">
                            {{ $items->links('pagination::tailwind') }}
                        </div>
                    @endif
                @endif
            </div>
            <div class="lg:col-span-4">
                @include('partials.articles-sidebar', ['recentArticles' => $sidebarArticles])
            </div>
        </div>
    </div>
</section>
@endsection
