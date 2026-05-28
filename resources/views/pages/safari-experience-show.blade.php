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

    $formatRichText = static function (?string $text): string {
        $value = trim((string) $text);
        if ($value === '') {
            return '';
        }

        $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];
        $chunks = [];
        $listItems = [];

        $flushList = static function () use (&$chunks, &$listItems): void {
            if ($listItems === []) {
                return;
            }

            $items = array_map(
                static fn (string $item): string => '<li>'.e(trim($item)).'</li>',
                $listItems
            );
            $chunks[] = '<ul class="list-disc space-y-1 pl-5">'.implode('', $items).'</ul>';
            $listItems = [];
        };

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                $flushList();
                continue;
            }

            if (preg_match('/^[-*•]\s+(.+)$/u', $trimmed, $m) === 1) {
                $listItems[] = $m[1];
                continue;
            }

            $flushList();
            $chunks[] = '<p>'.e($trimmed).'</p>';
        }

        $flushList();

        return implode('', $chunks);
    };
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
            <div class="lg:grid lg:grid-cols-12 lg:gap-10 xl:gap-14 2xl:gap-16">
                <div class="min-w-0 lg:col-span-8">
                    @if(filled($safariExperience->description))
                        <div class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8">
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Introduction') }}</h2>
                            <div class="prose prose-ink prose-site mt-4 max-w-none space-y-4 text-[1.03rem] leading-[1.85] text-ink/90 sm:text-[1.06rem] sm:leading-[1.9]">
                                {!! $formatRichText($safariExperience->description) !!}
                            </div>
                        </div>
                    @endif

                    @if($safariExperience->galleryImages->isNotEmpty())
                        <div
                            class="mt-8 rounded-card border border-secondary/30 bg-white/90 p-6 shadow-sm sm:p-8"
                            x-data="{
                                photos: @js($safariExperience->galleryImages->map(fn ($photo) => $photo->imageUrl())->values()->all()),
                                open: false,
                                index: 0,
                                show(i) { this.index = i; this.open = true; document.body.classList.add('overflow-hidden'); },
                                close() { this.open = false; document.body.classList.remove('overflow-hidden'); },
                                prev() { this.index = (this.index - 1 + this.photos.length) % this.photos.length; },
                                next() { this.index = (this.index + 1) % this.photos.length; }
                            }"
                            @keydown.escape.window="if (open) close()"
                            @keydown.arrow-left.window="if (open) prev()"
                            @keydown.arrow-right.window="if (open) next()"
                        >
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Gallery') }}</h2>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($safariExperience->galleryImages as $idx => $photo)
                                    <button type="button" @click="show({{ $idx }})" class="block overflow-hidden rounded-xl border border-secondary/35 bg-surface/40 text-left">
                                        <img src="{{ $photo->imageUrl() }}" alt="" class="h-44 w-full object-cover transition duration-300 hover:scale-[1.02]" loading="lazy">
                                    </button>
                                @endforeach
                            </div>

                            <div
                                x-show="open"
                                x-transition.opacity
                                class="fixed inset-0 z-[70] bg-black/85 p-4 sm:p-8"
                                style="display:none;"
                                @click.self="close()"
                            >
                                <div class="relative mx-auto flex h-full max-w-6xl items-center justify-center">
                                    <button type="button" @click="close()" class="absolute right-2 top-2 z-10 rounded-full bg-black/50 p-2 text-white hover:bg-black/70" aria-label="{{ __('Close gallery') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>

                                    <button type="button" @click="prev()" class="absolute left-1 sm:left-3 rounded-full bg-black/50 p-2 text-white hover:bg-black/70" aria-label="{{ __('Previous image') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>

                                    <img :src="photos[index]" alt="" class="max-h-[85vh] w-auto max-w-full rounded-xl border border-white/20 shadow-2xl">

                                    <button type="button" @click="next()" class="absolute right-1 sm:right-3 rounded-full bg-black/50 p-2 text-white hover:bg-black/70" aria-label="{{ __('Next image') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Itineraries for this safari') }}</h2>
                            <a href="{{ route('safari') }}" class="text-xs font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('All safari styles') }}</a>
                        </div>

                        @if(($relatedTours ?? collect())->isNotEmpty())
                            <div class="mt-4 space-y-5">
                                @foreach($relatedTours as $tour)
                                    <article class="rounded-xl border border-secondary/40 bg-gradient-to-b from-white to-surface/40 p-5 shadow-sm">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-[0.68rem] font-semibold text-primary">{{ $loop->iteration }}</span>
                                                <h3 class="text-base font-semibold text-ink">{{ $tour->title }}</h3>
                                            </div>
                                            @if($tour->duration_days)
                                                <p class="rounded-full border border-secondary/45 bg-white px-2.5 py-1 text-xs font-medium text-ink/60">{{ trans_choice(':days day|:days days', $tour->duration_days, ['days' => $tour->duration_days]) }}</p>
                                            @endif
                                        </div>

                                        @if(filled($tour->description))
                                            <div class="prose prose-ink prose-site mt-3 max-w-none space-y-3 text-sm leading-relaxed text-ink/80">
                                                {!! $formatRichText($tour->description) !!}
                                            </div>
                                        @endif

                                        @if($tour->itineraryDays->isNotEmpty())
                                            <div class="mt-4 border-t border-secondary/30 pt-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-primary">{{ __('Day by day') }}</p>
                                                <div class="mt-3 space-y-3 border-l-2 border-primary/15 pl-3 sm:pl-4">
                                                    @foreach($tour->itineraryDays as $day)
                                                        <div class="relative rounded-lg border border-secondary/30 bg-white/80 p-3 sm:p-3.5">
                                                            <span class="absolute -left-[1.05rem] top-4 hidden h-3.5 w-3.5 rounded-full border border-primary/30 bg-white sm:block"></span>
                                                            <p class="text-sm font-semibold text-ink">
                                                                {{ __('Day :day', ['day' => $day->day_number]) }}
                                                                @if(filled($day->title))
                                                                    — {{ $day->title }}
                                                                @endif
                                                            </p>
                                                            @if($day->imageUrl())
                                                                <div class="mt-2 overflow-hidden rounded-lg border border-secondary/30 bg-white">
                                                                    <img src="{{ $day->imageUrl() }}" alt="{{ __('Day :day image', ['day' => $day->day_number]) }}" class="h-48 w-full object-cover" loading="lazy">
                                                                </div>
                                                            @endif
                                                            @if(filled($day->body))
                                                                <div class="prose prose-ink prose-site mt-2 max-w-none space-y-2 text-sm leading-relaxed text-ink/75">
                                                                    {!! $formatRichText($day->body) !!}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-sm text-ink/65">{{ __('No itineraries have been added for this safari yet.') }}</p>
                        @endif
                    </div>
                </div>

                <aside class="mt-8 min-h-0 lg:col-span-4 lg:mt-0 lg:flex lg:flex-col" aria-label="{{ __('Safari style sidebar') }}">
                    @include('partials.safari-experience-sidebar', ['safariExperience' => $safariExperience, 'relatedTours' => $relatedTours])
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection
