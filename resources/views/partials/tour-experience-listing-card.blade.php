@php
    /** @var \App\Models\Tour $tour */
    $fallbackImg = $fallbackImg ?? 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=900&q=82';
    $aosDelay = (int) ($aosDelay ?? 0);
@endphp

<article
    class="group/card flex min-w-0 flex-col overflow-hidden rounded-3xl border border-secondary/20 bg-white shadow-[0_4px_28px_rgba(46,46,46,0.07)] ring-1 ring-black/[0.02] transition duration-300 ease-out hover:-translate-y-0.5 hover:border-primary/20 hover:shadow-[0_18px_48px_rgba(46,46,46,0.12)]"
    data-aos="fade-up"
    data-aos-delay="{{ $aosDelay }}"
>
    <a href="{{ route('experiences.show', $tour) }}" class="relative isolate block aspect-[16/10] overflow-hidden bg-gradient-to-br from-secondary/35 to-primary/[0.08]">
        @if($tour->imageUrl())
            <img
                src="{{ $tour->imageUrl() }}"
                alt="{{ __('Safari experience: :title', ['title' => $tour->title]) }}"
                class="h-full w-full object-cover transition duration-700 ease-out group-hover/card:scale-[1.04]"
                loading="lazy"
                width="640"
                height="400"
            >
        @else
            <img src="{{ $fallbackImg }}" alt="" class="h-full w-full object-cover opacity-95" loading="lazy" width="640" height="400">
        @endif
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent opacity-90 transition duration-300 group-hover/card:from-black/45" aria-hidden="true"></div>
        @if($tour->is_featured)
            <span class="absolute right-3 top-3 rounded-full border border-white/25 bg-white/90 px-2.5 py-1 text-[0.65rem] font-bold uppercase tracking-[0.14em] text-primary shadow-sm backdrop-blur-sm">{{ __('Featured') }}</span>
        @endif
        @if($tour->duration_days)
            <span class="absolute bottom-3 left-3 rounded-full border border-white/30 bg-black/35 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.12em] text-white backdrop-blur-md">
                {{ trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]) }}
            </span>
        @endif
    </a>
    <div class="flex flex-1 flex-col border-t border-secondary/10 bg-gradient-to-b from-white to-primary/[0.03] px-6 pb-6 pt-5">
        <h3 class="font-serif text-xl font-semibold leading-snug tracking-tight text-ink">
            <a href="{{ route('experiences.show', $tour) }}" class="transition hover:text-primary">{{ $tour->title }}</a>
        </h3>
        <div class="mt-5 border-t border-secondary/15 pt-4">
            <a
                href="{{ route('experiences.show', $tour) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition group-hover/card:gap-3"
            >
                {{ __('View full itinerary') }}
                <svg class="h-4 w-4 shrink-0 transition group-hover/card:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>
