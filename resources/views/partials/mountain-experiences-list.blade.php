@php
    /** @var \App\Models\Mountain $mountain */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $mountainTours */
@endphp

<section class="mt-12 border-t border-secondary/35 pt-12" aria-labelledby="mountain-experiences-heading">
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-secondary/25 pb-6">
        <div>
            <h2 id="mountain-experiences-heading" class="font-serif text-2xl font-semibold tracking-tight text-primary sm:text-[1.75rem]">
                {{ __('Safaris & experiences') }}: {{ $mountain->name }}
            </h2>
            <p class="mt-2 max-w-3xl text-sm leading-relaxed text-ink/65">
                @if($mountain->slug === 'mount-kenya')
                    {{ __('These treks and highland experiences are the ones we publish for Mount Kenya. Open any card, or use the list below, for the full itinerary.') }}
                @elseif(in_array($mountain->slug, ['mount-kilimanjaro', 'kilimanjaro'], true))
                    {{ __('Published Kilimanjaro programmes and any active itinerary that references the mountain appear below.') }}
                @endif
                {{ trans_choice('We publish :count active journey for this peak.|We publish :count active journeys for this peak.', $mountainTours->count(), ['count' => $mountainTours->count()]) }}
                {{ __('Open any card, or use the list below, for the full day-by-day experience page.') }}
            </p>
        </div>
        <a
            href="#plan-this-mountain"
            class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-primary/25 bg-primary/[0.06] px-4 py-2 text-xs font-semibold uppercase tracking-wide text-primary transition hover:bg-primary/[0.1]"
        >{{ __('Plan this peak') }}</a>
    </div>

    @if($mountainTours->isNotEmpty())
        <nav class="mt-8 rounded-3xl border border-secondary/25 bg-gradient-to-br from-white to-surface/90 p-6 shadow-[0_4px_24px_rgba(46,46,46,0.06)] sm:p-7" aria-labelledby="mountain-experiences-list-heading">
            <h3 id="mountain-experiences-list-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">
                {{ __('All experiences on this page') }}
            </h3>
            <ol class="mt-5 grid gap-x-10 gap-y-2.5 text-sm leading-snug text-ink/90 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($mountainTours as $tour)
                    <li class="min-w-0 border-b border-secondary/10 pb-2 sm:border-0 sm:pb-0">
                        <a href="{{ route('experiences.show', $tour) }}" class="font-medium text-primary underline decoration-primary/25 underline-offset-[3px] transition hover:decoration-primary">
                            <span class="mr-1.5 font-normal tabular-nums text-ink/40">{{ $loop->iteration }}.</span>{{ $tour->title }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="mt-10 grid gap-7 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($mountainTours as $tour)
            @include('partials.tour-experience-listing-card', ['tour' => $tour, 'aosDelay' => min(200, 40 * ($loop->index % 6))])
        @empty
            <div class="rounded-2xl border border-dashed border-secondary/50 bg-surface/70 px-6 py-12 text-center sm:col-span-2 xl:col-span-3">
                <p class="text-base font-medium text-ink">{{ __('No published itineraries match this mountain yet.') }}</p>
                <p class="mx-auto mt-2 max-w-lg text-sm text-ink/60">{{ __('Tell us your dates and goals: we will shape a route that includes this peak.') }}</p>
                <a href="{{ route('plan-my-safari', ['mountain' => $mountain->slug]) }}" class="btn-primary mt-6 inline-flex min-h-[2.85rem] items-center justify-center px-6">{{ __('Plan this safari') }}</a>
            </div>
        @endforelse
    </div>
</section>
