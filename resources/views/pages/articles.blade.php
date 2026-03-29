@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Articles').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Articles'), 'subtitle' => __('Stories from the field.')])

<section class="site-gutter-x mx-auto max-w-5xl section-y-compact bg-white section-divider">
    <div class="space-y-10">
        @forelse($items as $article)
            <article
                class="card-depth grid gap-6 p-6 md:grid-cols-3"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <div class="img-zoom-parent aspect-video overflow-hidden rounded-card bg-secondary/40 md:col-span-1">
                    @if($article->featured_image)
                        <img src="{{ $article->featuredImageUrl() }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                    @endif
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-accent">{{ optional($article->published_at)->format('M j, Y') }}</p>
                    <h2 class="mt-2 text-2xl font-semibold text-primary">
                        <a href="{{ route('articles.show', $article) }}" class="hover:underline">{{ $article->title }}</a>
                    </h2>
                    <p class="mt-2 text-ink/75">{{ \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->body), 200) }}</p>
                    <a href="{{ route('articles.show', $article) }}" class="mt-4 inline-flex text-sm font-semibold text-primary">{{ __('Read more') }} →</a>
                </div>
            </article>
        @empty
            <p class="text-center text-ink/60">{{ __('No articles yet.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
