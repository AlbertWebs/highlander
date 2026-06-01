@php
    /** @var \App\Models\SafariExperience $safari */
    $countryTag = $safari->homepageCountryTag();
    $typeTag = $safari->homepageSafariTypeLabel();
@endphp
<article
    class="group flex min-h-full flex-col overflow-hidden rounded-2xl border border-secondary/45 bg-white shadow-soft ring-1 ring-black/[0.03] transition duration-300 hover:border-primary/30 hover:shadow-md"
    data-aos="fade-up"
    data-aos-duration="750"
    data-aos-delay="{{ min(350, 60 * ($cardIndex ?? $loop->index ?? 0)) }}"
>
    <a href="{{ route('safari.show', $safari) }}" class="img-zoom-parent relative block aspect-[4/3] overflow-hidden bg-secondary/30">
        <img src="{{ $safari->cardImageUrl() }}" alt="" class="h-full w-full object-cover img-zoom-hover" loading="lazy">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>
        @if(filled($safari->duration))
            <p class="absolute bottom-3 left-3 rounded-md bg-white/95 px-2.5 py-1 text-[0.65rem] font-bold tracking-[0.12em] text-ink shadow-sm ring-1 ring-black/5">
                {{ $safari->duration }}
            </p>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5 sm:p-6">
        @if($countryTag || $typeTag)
            <p class="flex flex-wrap items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-ink/50">
                @if($countryTag)
                    <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-primary">{{ $countryTag }}</span>
                @endif
                @if($typeTag)
                    <span class="rounded-full bg-accent/25 px-2.5 py-0.5 text-ink/70">{{ $typeTag }}</span>
                @endif
            </p>
        @endif

        <h3 class="mt-3 font-serif text-lg font-semibold leading-snug text-ink transition group-hover:text-primary sm:text-[1.125rem]">
            <a href="{{ route('safari.show', $safari) }}" class="hover:text-primary hover:underline decoration-primary/30 underline-offset-2">{{ $safari->title }}</a>
        </h3>

        @if(filled($safari->description))
            <p class="mt-2 line-clamp-2 flex-1 text-sm leading-relaxed text-ink/70">{{ \Illuminate\Support\Str::limit(strip_tags((string) $safari->description), 140) }}</p>
        @endif

        <div class="mt-5 grid grid-cols-2 gap-2">
            <a
                href="{{ route('safari.show', $safari) }}"
                class="btn-outline inline-flex min-h-[2.5rem] items-center justify-center gap-1.5 px-2 py-2 text-[0.6875rem] font-semibold uppercase tracking-[0.06em] sm:text-xs"
            >
                <svg class="h-3.5 w-3.5 shrink-0 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('View details') }}
            </a>
            <a
                href="{{ route('plan-my-safari', ['safari' => $safari->slug]) }}"
                class="btn-primary inline-flex min-h-[2.5rem] items-center justify-center gap-1.5 bg-gradient-to-r from-primary via-primary to-accent px-2 py-2 text-[0.6875rem] font-semibold uppercase tracking-[0.06em] hover:brightness-110 sm:text-xs"
            >
                {{ __('Book now') }}
            </a>
        </div>
    </div>
</article>
