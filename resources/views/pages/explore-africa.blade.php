@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Explore Africa').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', [
    'title' => __('Explore Africa'),
    'subtitle' => __('From coastlines to savannas—discover where we travel.'),
    'wide' => true,
])

<section class="site-gutter-x w-full max-w-none section-y-compact bg-surface section-divider">
    <div class="mx-auto max-w-3xl text-center">
        <p class="text-lg leading-relaxed text-ink/80">
            {{ __('Each region has its own rhythm—open a destination to read more, then plan a safari shaped around the places that speak to you.') }}
        </p>
    </div>

    <div class="mt-12 grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-4">
        @forelse($items as $dest)
            <article
                id="dest-{{ $dest->slug }}"
                class="card-depth flex scroll-mt-28 flex-col overflow-hidden bg-white"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <a href="{{ route('explore-africa.show', $dest) }}" class="img-zoom-parent relative block aspect-[4/3] overflow-hidden bg-secondary/40">
                    <img src="{{ $dest->cardImageUrl() }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                </a>
                <div class="flex flex-1 flex-col p-6">
                    <h2 class="text-xl font-semibold text-primary">
                        <a href="{{ route('explore-africa.show', $dest) }}" class="transition hover:text-primary/85 hover:underline">{{ $dest->name }}</a>
                    </h2>
                    <p class="mt-2 flex-1 text-sm text-ink/75">{{ \Illuminate\Support\Str::limit(strip_tags((string) $dest->description), 160) }}</p>
                    <a
                        href="{{ route('explore-africa.show', $dest) }}"
                        class="btn-secondary mt-5 w-full text-center sm:w-auto"
                    >{{ __('Explore more') }}</a>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center text-ink/60">{{ __('Destinations coming soon.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>

    <div class="mt-16 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/[0.07] to-accent/[0.06] px-6 py-10 text-center sm:px-10">
        <h2 class="font-serif text-2xl font-semibold text-ink sm:text-3xl">{{ __('Ready to shape your trip?') }}</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-ink/75 sm:text-base">
            {{ __('Share your dates, group size, and must-see places—we will reply with ideas and a tailored outline.') }}
        </p>
        <a href="{{ route('plan-my-safari') }}" class="btn-primary mt-8 inline-flex min-w-[12rem] items-center justify-center bg-gradient-to-r from-primary via-primary to-accent px-8 py-3.5 hover:brightness-110">
            {{ __('Plan this safari') }}
        </a>
    </div>
</section>
@endsection
