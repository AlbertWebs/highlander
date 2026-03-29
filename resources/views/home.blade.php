@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : config('app.name').' — '.__('Discover Africa'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@php
    $h = trim($hero_headline);
    $words = preg_split('/\s+/u', $h, -1, PREG_SPLIT_NO_EMPTY);
    $heroHeadlineLast = null;
    $heroHeadlineFirst = $h;
    if (count($words) > 1) {
        $heroHeadlineLast = array_pop($words);
        $heroHeadlineFirst = implode(' ', $words);
    }
    $heroLink1Label = filled($hero_link_1_label) ? $hero_link_1_label : __('Signature journeys');
    $heroLink2Label = filled($hero_link_2_label) ? $hero_link_2_label : __('Mountain expeditions');
@endphp
<section class="relative min-h-[100svh] overflow-hidden">
    @if(! empty($hero_use_carousel))
        <div
            class="absolute inset-0"
            x-data="heroCarousel({ videos: @js($hero_videos), intervalSec: {{ (int) $hero_carousel_interval }} })"
        >
            <div class="absolute inset-0">
                @foreach($hero_videos as $i => $url)
                    <video
                        class="hero-bg-video absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-out"
                        src="{{ $url }}"
                        muted
                        loop
                        playsinline
                        poster="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1920&q=80"
                        :class="current === {{ $i }} ? 'z-10 opacity-100' : 'pointer-events-none z-0 opacity-0'"
                    ></video>
                @endforeach
            </div>
            <div class="pointer-events-none absolute bottom-36 left-1/2 z-20 flex -translate-x-1/2 gap-2 sm:bottom-40" role="tablist" aria-label="{{ __('Hero videos') }}">
                @foreach($hero_videos as $i => $url)
                    <button
                        type="button"
                        class="pointer-events-auto h-2 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/80"
                        :class="current === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/40'"
                        @click="goTo({{ $i }})"
                        :aria-selected="current === {{ $i }}"
                        aria-label="{{ __('Slide') }} {{ $i + 1 }}"
                    ></button>
                @endforeach
            </div>
        </div>
    @elseif(! empty($hero_uses_vimeo_embed) && ! empty($hero_vimeo_id))
        {{-- Full-bleed Vimeo background (autoplay, muted, loop) --}}
        <div class="absolute inset-0 z-0 overflow-hidden bg-black">
            <iframe
                class="pointer-events-none absolute left-1/2 top-1/2 h-[56.25vw] min-h-full w-[177.78vh] min-w-full max-w-none -translate-x-1/2 -translate-y-1/2 border-0"
                src="https://player.vimeo.com/video/{{ $hero_vimeo_id }}?badge=0&amp;autopause=0&amp;autoplay=1&amp;background=1&amp;byline=0&amp;loop=1&amp;muted=1&amp;playsinline=1&amp;portrait=0&amp;title=0&amp;transparent=0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
                title="{{ __('Hero video') }}"
            ></iframe>
        </div>
    @else
        <video
            class="absolute inset-0 h-full w-full object-cover"
            autoplay
            muted
            loop
            playsinline
            poster="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1920&q=80"
        >
            <source src="{{ $hero_videos[0] ?? $hero_video_url }}" type="video/mp4">
        </video>
    @endif
    {{-- Leave the centre visually open; darken edges like a stage --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/35 to-black/55"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/45 via-transparent to-black/80"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_85%_65%_at_50%_38%,transparent_0%,rgba(0,0,0,0.35)_55%,rgba(0,0,0,0.72)_100%)]"></div>

    <div class="relative z-10 flex min-h-[100svh] flex-col pt-[7.25rem] sm:pt-[8.5rem]">
        <div class="site-gutter-x flex flex-1 flex-col justify-center pb-10">
            <div class="max-w-xl lg:max-w-2xl" data-aos="fade-up" data-aos-duration="900" data-aos-delay="0">
                <h1 class="font-serif text-[2.35rem] font-medium leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-6xl lg:leading-[1.06]">
                    <span class="text-white">{{ $heroHeadlineFirst }}</span>@if($heroHeadlineLast)<span class="text-white/40"> {{ $heroHeadlineLast }}</span>@endif
                </h1>
                <a
                    href="{{ $hero_icon_url }}"
                    class="group mt-10 -ml-1 inline-flex items-center justify-center rounded-sm text-white transition duration-300 hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent"
                    aria-label="{{ __('Continue') }}"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="140"
                >
                    <svg
                        class="h-[3.5rem] w-[3.5rem] shrink-0 transition-transform duration-300 ease-out drop-shadow-[0_2px_16px_rgba(0,0,0,0.5)] sm:h-[4.5rem] sm:w-[4.5rem] lg:h-[5.25rem] lg:w-[5.25rem] group-hover:translate-x-1.5 group-hover:-translate-y-1.5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.15"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H9M17 7v8"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="site-gutter-x border-t border-white/15 bg-black/25 py-6 backdrop-blur-md" data-aos="fade-up" data-aos-duration="850" data-aos-delay="240">
            @if(filled($hero_subheadline))
                <div class="grid gap-5 md:grid-cols-[minmax(0,1fr)_minmax(0,min(36rem,55vw))_minmax(0,1fr)] md:items-end md:gap-6">
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-2 border-b border-white/15 pb-4 text-sm font-medium text-white/95 md:border-0 md:pb-0 md:justify-self-start">
                        <a href="{{ $hero_link_1_url }}" class="inline-flex items-center gap-2 transition hover:opacity-95">
                            <span class="text-white/95">{{ $heroLink1Label }}</span>
                            @include('partials.external-link-arrow')
                        </a>
                        <a href="{{ $hero_link_2_url }}" class="inline-flex items-center gap-2 transition hover:opacity-95">
                            <span class="text-white/95">{{ $heroLink2Label }}</span>
                            @include('partials.external-link-arrow')
                        </a>
                    </div>
                    <p class="text-center text-sm leading-relaxed text-white/85 md:justify-self-center">{{ $hero_subheadline }}</p>
                    <div class="hidden md:block" aria-hidden="true"></div>
                </div>
            @else
                <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-sm font-medium text-white/95">
                    <a href="{{ $hero_link_1_url }}" class="inline-flex items-center gap-2 transition hover:opacity-95">
                        <span class="text-white/95">{{ $heroLink1Label }}</span>
                        @include('partials.external-link-arrow')
                    </a>
                    <a href="{{ $hero_link_2_url }}" class="inline-flex items-center gap-2 transition hover:opacity-95">
                        <span class="text-white/95">{{ $heroLink2Label }}</span>
                        @include('partials.external-link-arrow')
                    </a>
                </div>
            @endif
        </div>
    </div>

    @include('partials.hero-social')
</section>

<section class="home-welcome-pr section-y bg-white section-divider">
    @php
        $welcomeLines = array_values(array_filter(array_map('trim', explode('|', (string) ($welcome_title ?? ''))), fn ($l) => $l !== ''));
        if (count($welcomeLines) === 0) {
            $welcomeLines = [__('Welcome')];
        }
        $rawWelcomeBody = trim((string) ($welcome_body ?? ''));
        $welcomeParas = [];
        if ($rawWelcomeBody !== '') {
            $welcomeParas = array_values(array_filter(preg_split('/\r\n\r\n|\n\n/', $rawWelcomeBody), fn ($p) => trim($p) !== ''));
            if (count($welcomeParas) === 0) {
                $welcomeParas = [$rawWelcomeBody];
            }
        }
        $learnUrl = trim((string) ($welcome_learn_more_url ?? ''));
        $welcomeLearnHref = $learnUrl !== '' && filter_var($learnUrl, FILTER_VALIDATE_URL) ? $learnUrl : route('plan-my-safari');
        $welcomeLearnLabel = filled(trim((string) ($welcome_learn_more_label ?? ''))) ? trim((string) $welcome_learn_more_label) : __('Plan My Safari');
        $c1 = trim((string) ($welcome_card_1_link ?? ''));
        $welcomeCard1Href = $c1 !== '' && filter_var($c1, FILTER_VALIDATE_URL) ? $c1 : route('safari');
        $c2 = trim((string) ($welcome_card_2_link ?? ''));
        $welcomeCard2Href = $c2 !== '' && filter_var($c2, FILTER_VALIDATE_URL) ? $c2 : route('articles');
        $img1Raw = trim((string) ($welcome_card_1_image_url ?? ''));
        $welcomeImg1 = filled($img1Raw) ? $img1Raw : 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&h=1200&q=80';
        $img2Raw = trim((string) ($welcome_card_2_image_url ?? ''));
        $welcomeImg2 = filled($img2Raw) ? $img2Raw : 'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?auto=format&fit=crop&w=900&h=1200&q=80';
    @endphp
    {{-- Same horizontal inset as hero headline (site-gutter-x only — no max-width centering) --}}
    <div class="site-gutter-x">
        <div
            class="grid grid-cols-1 items-stretch gap-12 md:grid-cols-2 md:gap-x-8 md:gap-y-12 lg:grid-cols-12 lg:gap-x-8 lg:gap-y-0 xl:gap-x-12"
        >
            {{-- Column 1: headline, body, primary CTA — wider on lg for readable measure --}}
            <div class="flex min-w-0 flex-col justify-center md:col-span-2 lg:col-span-5 lg:pr-3 xl:pr-6" data-aos="fade-right" data-aos-duration="800">
                <h2 class="mb-4 font-serif text-[1.875rem] font-semibold leading-[1.18] tracking-tight text-ink sm:text-[2.125rem] lg:text-[2.375rem] lg:leading-[1.15] xl:text-[2.5rem]">
                    @foreach($welcomeLines as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h2>
                @if(count($welcomeParas))
                    <div class="mt-6 max-w-prose space-y-5 text-base leading-[1.65] text-ink/85 sm:mt-7 sm:text-[1.0625rem] lg:text-lg lg:leading-relaxed">
                        @foreach($welcomeParas as $para)
                            <p>{{ $para }}</p>
                        @endforeach
                    </div>
                @endif
                <a
                    href="{{ $welcomeLearnHref }}"
                    class="btn-primary mt-9 w-full px-8 py-3.5 sm:mt-10 sm:w-fit"
                >{{ $welcomeLearnLabel }}</a>
            </div>

            {{-- Column 2: portrait image card --}}
            <a
                href="{{ $welcomeCard1Href }}"
                class="img-zoom-parent group relative block min-h-[min(88vw,440px)] overflow-hidden rounded-card bg-secondary/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-ink/20 focus-visible:ring-offset-2 focus-visible:ring-offset-surface md:min-h-[min(42vw,420px)] lg:col-span-3 lg:min-h-[min(34vw,560px)]"
                data-aos="zoom-in"
                data-aos-duration="850"
                data-aos-delay="100"
            >
                @include('partials.welcome-card-media', [
                    'vimeoId' => ($welcome_card_1_media_type ?? 'image') === 'vimeo' ? ($welcome_card_1_vimeo_id ?? null) : null,
                    'imageUrl' => $welcomeImg1,
                ])
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-black/25"></div>
                <div class="absolute inset-x-0 bottom-0 flex flex-col justify-end p-6 sm:p-7 lg:p-8">
                    @if(filled(trim((string) ($welcome_card_1_overlay ?? ''))))
                        <p class="max-w-[15rem] text-[1.0625rem] font-medium leading-snug text-white sm:max-w-none sm:text-lg lg:text-xl lg:leading-snug">{{ trim((string) $welcome_card_1_overlay) }}</p>
                    @endif
                    <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white/95 sm:text-[0.9375rem]">
                        <span class="underline decoration-white/90 underline-offset-[6px]">{{ __('Read more') }}</span>
                        @include('partials.external-link-arrow')
                    </span>
                </div>
            </a>

            {{-- Column 3: portrait image + serif stat --}}
            <a
                href="{{ $welcomeCard2Href }}"
                class="img-zoom-parent group relative block min-h-[min(88vw,440px)] overflow-hidden rounded-card bg-secondary/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-ink/20 focus-visible:ring-offset-2 focus-visible:ring-offset-surface md:min-h-[min(42vw,420px)] lg:col-span-4 lg:min-h-[min(34vw,560px)]"
                data-aos="zoom-in"
                data-aos-duration="850"
                data-aos-delay="200"
            >
                @include('partials.welcome-card-media', [
                    'vimeoId' => ($welcome_card_2_media_type ?? 'image') === 'vimeo' ? ($welcome_card_2_vimeo_id ?? null) : null,
                    'imageUrl' => $welcomeImg2,
                ])
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-black/25"></div>
                @if(filled(trim((string) ($welcome_card_2_stat ?? ''))))
                    <p class="pointer-events-none absolute left-6 top-6 font-serif text-5xl font-normal leading-none tracking-tight text-white sm:left-8 sm:top-8 sm:text-6xl lg:text-7xl">{{ trim((string) $welcome_card_2_stat) }}</p>
                @endif
                <div class="absolute inset-x-0 bottom-0 flex flex-col justify-end p-6 sm:p-7 lg:p-8">
                    @if(filled(trim((string) ($welcome_card_2_overlay ?? ''))))
                        <p class="max-w-[16rem] text-[1.0625rem] font-medium leading-snug text-white sm:max-w-none sm:text-lg lg:text-xl lg:leading-snug">{{ trim((string) $welcome_card_2_overlay) }}</p>
                    @endif
                    <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white/95 sm:text-[0.9375rem]">
                        <span class="underline decoration-white/90 underline-offset-[6px]">{{ __('Read more') }}</span>
                        @include('partials.external-link-arrow')
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- Featured Experiences: light green tint + four columns from lg --}}
<section
    class="home-featured-experiences relative overflow-hidden bg-tint-green section-divider"
    aria-labelledby="featured-experiences-heading"
>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_0%,rgba(76,175,80,0.12),transparent_55%)]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-20 -top-28 h-72 w-72 rounded-full bg-primary/20 blur-3xl" aria-hidden="true"></div>
    <div class="relative site-gutter-x section-y">
        <header class="mx-auto max-w-3xl text-center lg:mx-0 lg:max-w-2xl lg:text-left" data-aos="fade-up" data-aos-duration="800">
            <p class="inline-flex items-center gap-2 rounded-full border border-primary/25 bg-white/70 px-4 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary shadow-sm backdrop-blur-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-accent shadow-[0_0_10px_rgba(139,195,74,0.9)]" aria-hidden="true"></span>
                {{ __('Experiences') }}
            </p>
            <h2 id="featured-experiences-heading" class="mt-4 mb-8 bg-gradient-to-r from-primary via-accent to-ink bg-clip-text font-serif text-[1.875rem] font-semibold leading-[1.15] tracking-tight text-transparent sm:text-[2.125rem] lg:text-[2.5rem]">
                {{ __('Featured Experiences') }}
            </h2>
            <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-ink/80 sm:text-lg lg:mx-0">
                {{ __('Hand-picked adventures for discerning travellers.') }}
            </p>
        </header>

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-7 lg:grid-cols-4 lg:gap-6 xl:gap-8">
            @forelse($featured_tours as $tour)
                <article
                    class="group flex min-w-0 flex-col overflow-hidden card-depth ring-1 ring-primary/10 backdrop-blur-sm"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="{{ min(400, 100 * $loop->index) }}"
                >
                    <div class="h-1.5 w-full bg-gradient-to-r from-primary via-accent to-primary bg-[length:200%_100%] bg-left transition duration-500 group-hover:bg-right"></div>
                    <div class="img-zoom-parent relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-secondary/50 to-primary/20">
                        @include('partials.tour-featured-media', ['tour' => $tour])
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/60 via-black/5 to-primary/10 opacity-90 transition duration-300 group-hover:from-black/50 group-hover:via-transparent group-hover:to-primary/20"></div>
                        @if($tour->price)
                            <p class="absolute bottom-3 left-3 rounded-full border border-white/30 bg-primary px-3 py-1 text-xs font-bold tabular-nums text-white shadow-lg ring-2 ring-primary/30">{{ __('From') }} ${{ number_format($tour->price, 0) }}</p>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col bg-gradient-to-b from-white via-white to-primary/[0.04] p-5 sm:p-6">
                        <h3 class="font-serif text-lg font-semibold leading-snug tracking-tight text-ink transition group-hover:text-primary lg:text-[1.125rem]">{{ $tour->title }}</h3>
                        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-ink/75">{{ $tour->description }}</p>
                        {{-- Narrow 4-up cards: stack CTAs from lg; smaller type + padding so labels read cleanly --}}
                        <div class="mt-5 grid grid-cols-2 gap-2 sm:gap-2.5 lg:grid-cols-1 lg:gap-2">
                            <a
                                href="{{ route('plan-my-safari', ['tour' => $tour->slug]) }}"
                                class="btn-primary min-w-0 w-full justify-center bg-gradient-to-r from-primary via-primary to-accent px-2.5 py-2 text-[0.625rem] leading-tight tracking-[0.04em] hover:brightness-110 sm:px-3 sm:text-[0.6875rem] sm:tracking-[0.05em] lg:py-2 lg:text-xs"
                            >{{ __('Plan This Safari') }}</a>
                            <a
                                href="{{ route('experiences.show', $tour) }}"
                                class="btn-secondary min-w-0 w-full justify-center px-2.5 py-2 text-[0.625rem] leading-tight tracking-[0.04em] sm:px-3 sm:text-[0.6875rem] sm:tracking-[0.05em] lg:py-2 lg:text-xs"
                            >{{ __('Explore this Safari') }}</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full max-w-prose text-center text-base leading-relaxed text-ink/65 sm:col-span-2 lg:col-span-4">{{ __('Add featured tours from the admin panel.') }}</p>
            @endforelse
        </div>
    </div>
</section>

<section class="relative overflow-hidden bg-white section-divider" aria-labelledby="why-choose-heading">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_50%_-20%,rgba(76,175,80,0.08),transparent_55%)]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute bottom-0 right-0 h-64 w-64 translate-x-1/4 translate-y-1/4 rounded-full bg-accent/10 blur-3xl" aria-hidden="true"></div>
    <div class="relative site-gutter-x mx-auto max-w-7xl section-y">
        <header class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ filled(trim((string) ($why_choose_eyebrow ?? ''))) ? $why_choose_eyebrow : __('Why us') }}</p>
            <h2 id="why-choose-heading" class="mt-3 mb-8 font-serif text-[1.875rem] font-semibold leading-tight tracking-tight text-ink sm:text-[2.25rem] lg:text-[2.5rem]">
                {{ filled(trim((string) ($why_choose_title ?? ''))) ? $why_choose_title : __('Why Choose Us') }}
            </h2>
            @if(filled(trim((string) ($why_choose_subtitle ?? ''))))
                <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-ink/75 sm:text-lg">{{ $why_choose_subtitle }}</p>
            @endif
        </header>
        <div class="mx-auto mt-12 grid max-w-6xl gap-6 sm:grid-cols-2 lg:mt-14 lg:grid-cols-3 lg:gap-8">
            @foreach($why_choose_items as $box)
                <article
                    class="group flex min-h-[12rem] flex-col card-depth p-8 sm:min-h-[13rem] sm:p-9"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="{{ min(400, 100 * $loop->index) }}"
                >
                    <div class="flex items-start gap-4 sm:gap-6">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary/15 via-primary/10 to-accent/20 text-3xl shadow-inner ring-1 ring-primary/10" aria-hidden="true">
                            <span class="leading-none">{{ $box['icon'] !== '' ? $box['icon'] : '·' }}</span>
                        </div>
                        <div class="min-w-0 flex-1 text-left">
                            <h3 class="font-serif text-xl font-semibold leading-snug text-ink transition group-hover:text-primary">{{ $box['title'] }}</h3>
                            @if(filled($box['body'] ?? ''))
                                <p class="mt-2 text-sm leading-relaxed text-ink/75 sm:text-[0.9375rem]">{{ $box['body'] }}</p>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="relative overflow-hidden bg-surface section-divider section-y" aria-labelledby="popular-destinations-heading">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent" aria-hidden="true"></div>
    <div class="relative site-gutter-x">
        <header class="mx-auto max-w-3xl text-center lg:mx-0 lg:max-w-2xl lg:text-left" data-aos="fade-up" data-aos-duration="800">
            <p class="inline-flex items-center gap-2 rounded-full border border-primary/25 bg-white/70 px-4 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary shadow-sm backdrop-blur-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-accent shadow-[0_0_10px_rgba(139,195,74,0.9)]" aria-hidden="true"></span>
                {{ __('Destinations') }}
            </p>
            <h2 id="popular-destinations-heading" class="mt-4 mb-8 font-serif text-3xl font-semibold tracking-tight text-primary sm:text-4xl">{{ __('Popular Destinations') }}</h2>
            <p class="mx-auto mt-5 max-w-xl text-sm leading-relaxed text-ink/80 sm:text-base lg:mx-0">{{ __('From savanna to coast—explore places we know by heart.') }}</p>
        </header>
    </div>

    @if($destinations->isEmpty())
        <p class="site-gutter-x mt-12 text-center text-ink/60 lg:text-left">{{ __('Destinations coming soon.') }}</p>
    @else
        {{-- Reduced motion: static grid --}}
        <div class="destinations-marquee-fallback site-gutter-x mt-12">
            @foreach($destinations as $dest)
                <div class="min-w-0" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ min(400, 100 * $loop->index) }}">
                    @include('partials.destination-card-home', ['dest' => $dest, 'fluid' => true])
                </div>
            @endforeach
        </div>

        {{-- Continuous marquee: one flex row, duplicated items; -50% = one full pass --}}
        <div class="destinations-marquee-live relative mt-10 md:mt-12">
            <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-12 bg-gradient-to-r from-secondary/35 to-transparent sm:w-20" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-12 bg-gradient-to-l from-secondary/35 to-transparent sm:w-20" aria-hidden="true"></div>
            <div class="overflow-hidden pb-2 pt-1">
                <div class="destinations-marquee-track flex w-max gap-6">
                    @foreach($destinations as $dest)
                        @include('partials.destination-card-home', ['dest' => $dest])
                    @endforeach
                    @foreach($destinations as $dest)
                        @include('partials.destination-card-home', ['dest' => $dest])
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>

@php
    $testimonialSlides = $testimonials->isEmpty() ? collect() : $testimonials->chunk(3);
@endphp
<section class="bg-white section-divider section-y">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <h2 class="mb-8 text-center font-serif text-3xl font-semibold text-primary sm:text-4xl" data-aos="fade-up" data-aos-duration="800">{{ __('Testimonials') }}</h2>
        @if($testimonials->isEmpty())
            <p class="text-center text-ink/60">{{ __('Testimonials will appear here.') }}</p>
        @else
            <div
                class="rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                x-data="testimonialCarousel({ total: {{ $testimonialSlides->count() }} })"
                role="region"
                tabindex="0"
                aria-roledescription="{{ __('carousel') }}"
                aria-label="{{ __('Testimonials') }}"
                @keydown.left.prevent="prev()"
                @keydown.right.prevent="next()"
            >
                <div class="flex items-stretch gap-2 sm:gap-4">
                    <button
                        type="button"
                        class="hidden h-11 w-11 shrink-0 self-center rounded-full border border-secondary/50 bg-white p-0 text-primary shadow-depth transition duration-300 ease-out hover:border-primary/30 hover:shadow-depth-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:flex sm:items-center sm:justify-center"
                        x-show="total > 1"
                        x-cloak
                        @click="prev()"
                        aria-label="{{ __('Previous testimonials') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <div class="min-w-0 flex-1 overflow-hidden">
                        <div
                            class="flex transition-transform duration-500 ease-out motion-reduce:transition-none motion-reduce:duration-0"
                            :style="`transform: translateX(-${current * 100}%)`"
                        >
                            @foreach($testimonialSlides as $slide)
                                <div class="min-w-full shrink-0 px-0.5 sm:px-1">
                                    <div class="grid gap-6 md:grid-cols-3 md:gap-8">
                                        @foreach($slide as $t)
                                            @php
                                                $rating = min(5, max(1, (int) ($t->rating ?? 5)));
                                                $initials = \Illuminate\Support\Str::of($t->name)->explode(' ')->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                                            @endphp
                                            <blockquote class="card-depth flex min-h-[280px] flex-col p-6 sm:min-h-[300px] sm:p-8">
                                                <div class="flex flex-col gap-5 sm:flex-row sm:gap-6">
                                                    @if($t->image)
                                                        <img src="{{ $t->imageUrl() }}" alt="" class="mx-auto h-16 w-16 shrink-0 rounded-full object-cover shadow-depth ring-2 ring-primary/15 sm:mx-0 sm:h-[4.5rem] sm:w-[4.5rem]" loading="lazy">
                                                    @else
                                                        <div class="mx-auto flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary/20 to-accent/25 font-serif text-xl font-semibold text-primary shadow-depth ring-2 ring-primary/10 sm:mx-0 sm:h-[4.5rem] sm:w-[4.5rem] sm:text-2xl" aria-hidden="true">{{ $initials }}</div>
                                                    @endif
                                                    <div class="min-w-0 flex-1 text-center sm:text-left">
                                                        <div class="flex justify-center gap-0.5 text-amber-500 sm:justify-start" role="img" aria-label="{{ $rating }}/5">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <span class="text-lg leading-none {{ $i <= $rating ? 'text-amber-500' : 'text-ink/15' }}" aria-hidden="true">★</span>
                                                            @endfor
                                                        </div>
                                                        <p class="mt-4 text-base leading-relaxed text-ink/90 sm:text-lg">“{{ $t->quote }}”</p>
                                                        <footer class="mt-6 border-t border-secondary/35 pt-5">
                                                            <cite class="not-italic font-serif text-lg font-semibold text-primary">{{ $t->name }}</cite>
                                                            @if(filled($t->country))
                                                                <p class="mt-1 text-sm text-ink/65">{{ $t->country }}</p>
                                                            @elseif(filled($t->role))
                                                                <p class="mt-1 text-sm text-ink/65">{{ $t->role }}</p>
                                                            @endif
                                                            @if(filled($t->safari_type))
                                                                <p class="mt-2 inline-block rounded-badge border border-primary/25 bg-primary/[0.06] px-2.5 py-1 text-xs font-medium text-primary">{{ $t->safari_type }}</p>
                                                            @endif
                                                        </footer>
                                                    </div>
                                                </div>
                                            </blockquote>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button
                        type="button"
                        class="hidden h-11 w-11 shrink-0 self-center rounded-full border border-secondary/50 bg-white p-0 text-primary shadow-depth transition duration-300 ease-out hover:border-primary/30 hover:shadow-depth-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:flex sm:items-center sm:justify-center"
                        x-show="total > 1"
                        x-cloak
                        @click="next()"
                        aria-label="{{ __('Next testimonials') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" />
                        </svg>
                    </button>
                </div>
                @if($testimonialSlides->count() > 1)
                    <div class="mt-8 flex justify-center gap-2" role="tablist" aria-label="{{ __('Testimonial slides') }}">
                        @foreach($testimonialSlides as $slide)
                            <button
                                type="button"
                                class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary motion-reduce:transition-none"
                                :class="current === {{ $loop->index }} ? 'w-8 bg-primary' : 'w-2 bg-secondary/40'"
                                @click="goTo({{ $loop->index }})"
                                :aria-selected="current === {{ $loop->index }}"
                                aria-label="{{ __('Slide') }} {{ $loop->iteration }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>

<section class="relative overflow-hidden bg-gradient-to-br from-primary via-primary to-[#2E7D32] section-y text-white section-divider" aria-labelledby="home-cta-heading">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_120%,rgba(0,0,0,0.2),transparent_55%)]" aria-hidden="true"></div>
    <div class="absolute inset-0 opacity-25" style="background-image: url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;"></div>
    <div class="site-gutter-x relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
        <h2 id="home-cta-heading" class="mb-4 font-serif text-3xl font-semibold tracking-tight sm:text-4xl lg:text-[2.75rem]">{{ $cta_title }}</h2>
        <p class="mb-8 text-lg leading-relaxed text-white/95">{{ $cta_body }}</p>
        <a
            href="{{ $cta_button_href }}"
            class="inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center rounded-btn bg-white px-10 py-3.5 text-center text-base font-semibold text-primary shadow-lg transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-white/95 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary"
        >{{ $cta_button_text }}</a>
    </div>
</section>
@endsection
