@php
    $popularSafaris = $popular_safaris ?? collect();
    $sectionTitle = filled(trim((string) ($popular_tours_title ?? '')))
        ? trim((string) $popular_tours_title)
        : __('Most Popular Tours');
    $sectionSubtitle = trim((string) ($popular_tours_subtitle ?? ''));
@endphp
@if($popularSafaris->isNotEmpty())
<section
    class="home-popular-tours relative overflow-hidden bg-white section-divider section-y"
    aria-labelledby="popular-tours-heading"
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-32 top-1/4 h-64 w-64 rounded-full bg-primary/[0.06] blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 h-56 w-56 rounded-full bg-accent/[0.12] blur-3xl" aria-hidden="true"></div>

    <div class="relative site-gutter-x">
        <header class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.32em] text-primary">{{ config('app.name') }}</p>
            <h2 id="popular-tours-heading" class="mt-3 font-serif text-[1.75rem] font-semibold uppercase leading-tight tracking-tight text-ink sm:text-[2rem] lg:text-[2.25rem]">
                {{ $sectionTitle }}
            </h2>
            @if($sectionSubtitle !== '')
                <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink/75 sm:text-lg">
                    {{ $sectionSubtitle }}
                </p>
            @endif
        </header>

        <div class="mt-10 grid grid-cols-1 gap-6 sm:mt-12 sm:grid-cols-2 sm:gap-7 lg:grid-cols-3 xl:grid-cols-4 xl:gap-8">
            @foreach($popularSafaris as $safari)
                @include('partials.popular-safari-card-home', ['safari' => $safari, 'cardIndex' => $loop->index])
            @endforeach
        </div>

        <p class="mt-10 text-center sm:mt-12" data-aos="fade-up" data-aos-duration="700">
            <a
                href="{{ route('safari') }}"
                class="btn-outline inline-flex min-h-[2.75rem] items-center justify-center gap-2 px-8 py-3 text-sm font-semibold"
            >
                {{ __('Explore all safari experiences') }}
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </p>
    </div>
</section>
@endif
