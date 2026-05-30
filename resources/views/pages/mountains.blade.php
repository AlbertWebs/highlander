@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Mountains').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', [
    'title' => __('Mountains'),
    'subtitle' => __('Summits, ridges, and alpine light across Africa.'),
    'wide' => true,
])

<section class="site-gutter-x w-full max-w-none section-y-compact bg-surface section-divider">
    <header class="mx-auto max-w-2xl text-center">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Trekking & peaks') }}</p>
        <p class="mt-4 text-lg leading-relaxed text-ink/80 sm:text-xl">
            {{ __('Browse peaks we guide on—tap a row for the full story, elevation, and how to plan your trek.') }}
        </p>
        @if($items->total() > 0)
            <p class="mt-3 text-sm text-ink/50">
                @if($items->total() === 1)
                    {{ __('1 peak listed') }}
                @else
                    {{ __(':count peaks listed', ['count' => $items->total()]) }}
                @endif
            </p>
        @endif
    </header>

    <div class="mt-12 lg:grid lg:grid-cols-12 lg:items-stretch lg:gap-10 xl:gap-12">
        <div class="min-w-0 lg:col-span-8">
            <div class="flex flex-col gap-6 lg:gap-8">
                @forelse($items as $mountain)
                    <article
                        id="mountain-{{ $mountain->slug }}"
                        class="card-depth group scroll-mt-28 overflow-hidden rounded-2xl border border-secondary/40 bg-white shadow-depth transition duration-300 hover:border-primary/20 hover:shadow-depth-hover"
                        data-aos="fade-up"
                        data-aos-duration="700"
                        data-aos-delay="{{ min(350, 80 * $loop->index) }}"
                    >
                        <div class="flex flex-col md:flex-row md:items-stretch">
                            <a
                                href="{{ route('mountains.show', $mountain) }}"
                                class="img-zoom-parent relative block aspect-[16/10] shrink-0 overflow-hidden bg-secondary/35 md:w-[42%] md:max-w-none md:aspect-auto md:min-h-[220px] lg:min-h-[240px]"
                                aria-label="{{ __('View :name', ['name' => $mountain->name]) }}"
                            >
                                @if($mountain->image)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image) }}"
                                        alt=""
                                        class="img-zoom-hover h-full w-full object-cover"
                                        loading="lazy"
                                        width="640"
                                        height="400"
                                    >
                                @else
                                    <div class="flex h-full min-h-[12rem] items-center justify-center bg-gradient-to-br from-secondary/50 via-surface to-primary/[0.12] md:min-h-full">
                                        <svg class="h-14 w-14 text-primary/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l2.25 3 3.75-4.5L21.75 18M4.5 5.25h15M3.75 21h16.5" />
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="flex flex-1 flex-col justify-center px-6 py-6 sm:px-8 sm:py-7 md:pl-8 md:pr-8">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($mountain->elevation_m)
                                        <span class="inline-flex items-center rounded-full border border-primary/25 bg-primary/[0.08] px-2.5 py-0.5 text-[0.7rem] font-semibold uppercase tracking-wide text-primary">
                                            {{ number_format($mountain->elevation_m) }} {{ __('m') }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="mt-3 font-serif text-2xl font-semibold leading-tight tracking-tight text-primary sm:text-[1.65rem]">
                                    <a href="{{ route('mountains.show', $mountain) }}" class="transition hover:text-primary/85 hover:underline">
                                        {{ $mountain->name }}
                                    </a>
                                </h2>
                                <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-ink/75 sm:line-clamp-2 sm:text-[0.9375rem]">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($mountain->description), 220) }}
                                </p>
                                <div class="mt-5 flex flex-col gap-2.5 border-t border-secondary/30 pt-5">
                                    <a
                                        href="{{ route('plan-my-safari', ['mountain' => $mountain->slug]) }}"
                                        class="btn-primary inline-flex min-h-[2.85rem] w-full items-center justify-center gap-2 bg-gradient-to-r from-primary via-primary to-accent px-5 py-2.5 shadow-md ring-1 ring-white/25 transition hover:brightness-110 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                                    >
                                        <svg class="h-4 w-4 shrink-0 opacity-95" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ __('Plan safari') }}
                                    </a>
                                    <a
                                        href="{{ route('mountains.show', $mountain) }}"
                                        class="btn-secondary inline-flex min-h-[2.75rem] w-full items-center justify-center px-5 py-2.5"
                                    >{{ __('Explore mountain') }}</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-secondary/60 bg-white px-8 py-16 text-center">
                        <p class="font-medium text-ink">{{ __('No mountains listed yet.') }}</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-ink/60">{{ __('Check back soon, or contact us to plan a trek.') }}</p>
                        <a href="{{ route('contact') }}" class="btn-primary mt-6 inline-flex">{{ __('Get in touch') }}</a>
                    </div>
                @endforelse
            </div>

            @if($items->hasPages())
                <div class="mt-12 flex justify-center border-t border-secondary/40 pt-10">
                    <div class="w-full max-w-full overflow-x-auto">{{ $items->links() }}</div>
                </div>
            @endif
        </div>

        <aside class="mt-10 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Planning links and sample itineraries') }}">
            @include('partials.mountains-index-sidebar', ['featuredTours' => $featuredTours])
        </aside>
    </div>

    <div class="mt-16 rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/[0.07] to-accent/[0.06] px-6 py-10 text-center sm:px-10">
        <h2 class="font-serif text-2xl font-semibold text-ink sm:text-3xl">{{ __('Not sure which peak fits you?') }}</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-ink/75 sm:text-base">
            {{ __('Tell us your dates, fitness level, and whether you want to combine with a safari—we will suggest a route.') }}
        </p>
        <a href="{{ route('plan-my-safari') }}" class="btn-primary mt-8 inline-flex min-w-[12rem] items-center justify-center bg-gradient-to-r from-primary via-primary to-accent px-8 py-3.5 hover:brightness-110">
            {{ __('Plan this safari') }}
        </a>
    </div>
</section>
@endsection
