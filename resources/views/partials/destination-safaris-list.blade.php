@php
    /** @var \App\Models\Destination $destination */
    /** @var \Illuminate\Support\Collection<int, \App\Models\SafariExperience> $countrySafaris */
    /** @var string|null $countryCode */
    /** @var array<string, string>|null $countryMeta */
    $heading = filled($countryMeta['title'] ?? null)
        ? $countryMeta['title']
        : __('Safaris in :region', ['region' => $destination->name]);
@endphp

@if($countrySafaris->isNotEmpty())
    <section class="mt-12 border-t border-primary/15 pt-12" aria-labelledby="destination-safaris-heading">
        <div class="flex flex-col gap-5 border-b border-secondary/25 pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0 max-w-3xl">
                <p class="inline-flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">
                    <span class="h-1 w-6 rounded-full bg-gradient-to-r from-primary to-accent" aria-hidden="true"></span>
                    {{ __('Safari styles') }}
                </p>
                <h2 id="destination-safaris-heading" class="mt-3 font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.875rem] lg:text-[2rem]">
                    <span class="bg-gradient-to-r from-primary via-primary to-accent bg-clip-text text-transparent">{{ $heading }}</span>
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-ink/70 sm:text-[0.9375rem]">
                    {{ trans_choice(
                        'Browse :count curated safari experience we run in this country—each opens to full itineraries, day-by-day detail, and planning.|Browse :count curated safari experiences we run in this country—each opens to full itineraries, day-by-day detail, and planning.',
                        $countrySafaris->count(),
                        ['count' => $countrySafaris->count()]
                    ) }}
                </p>
            </div>
            @if($countryCode)
                <a
                    href="{{ route('safari', ['country' => $countryCode]) }}"
                    class="btn-primary inline-flex shrink-0 items-center gap-2 self-start px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.12em] sm:self-end"
                >
                    {{ $countryMeta['view_all'] ?? __('View all safaris') }}
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endif
        </div>

        <nav class="mt-8 rounded-2xl border border-secondary/30 bg-gradient-to-br from-white via-surface/50 to-primary/[0.04] p-5 shadow-sm sm:p-6" aria-labelledby="destination-safaris-index-heading">
            <h3 id="destination-safaris-index-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">
                {{ __('On this page') }}
            </h3>
            <ol class="mt-4 grid gap-x-8 gap-y-2 sm:grid-cols-2">
                @foreach($countrySafaris as $safari)
                    <li class="min-w-0">
                        <a
                            href="{{ route('safari.show', $safari) }}"
                            class="group/link flex items-baseline gap-2 text-sm font-medium text-ink/85 transition hover:text-primary"
                        >
                            <span class="shrink-0 font-normal tabular-nums text-ink/40">{{ $loop->iteration }}.</span>
                            <span class="line-clamp-2 underline decoration-primary/0 decoration-2 underline-offset-2 transition group-hover/link:decoration-primary/50">{{ $safari->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>

        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-7 xl:grid-cols-2 xl:gap-8">
            @foreach($countrySafaris as $safari)
                @include('partials.featured-safari-card', [
                    'safari' => $safari,
                    'cardIndex' => $loop->index,
                ])
            @endforeach
        </div>
    </section>
@endif
