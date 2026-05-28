@php
    /** @var \App\Models\SafariExperience $safariExperience */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $relatedTours */
    /** @var \Illuminate\Support\Collection<int, \App\Models\SafariExperience> $otherSafariStyles */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $mountainExperiences */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $exploreAfricaExperiences */
    $tourFallbackImg = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=400&q=80';
@endphp

<div class="space-y-6 lg:sticky lg:top-24 lg:z-10 xl:top-28">
    <div class="overflow-hidden rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/[0.08] via-white to-tint-green/35 shadow-card ring-1 ring-primary/10" aria-labelledby="safari-glance-heading">
        <div class="border-b border-primary/10 bg-primary/[0.06] px-5 py-4">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/15" aria-hidden="true">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20h12M6 16h12M4 6h16M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2"/></svg>
                </span>
                <div>
                    <p id="safari-glance-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">{{ __('At a glance') }}</p>
                    <p class="mt-0.5 text-xs text-ink/55">{{ __('Safari style') }}</p>
                </div>
            </div>
        </div>
        <div class="px-5 py-5">
            <p class="font-serif text-xl font-semibold leading-snug tracking-tight text-ink sm:text-2xl">
                {{ $safariExperience->title }}
            </p>
            @if(filled($safariExperience->duration))
                <p class="mt-2 text-sm font-medium text-primary/90">{{ $safariExperience->duration }}</p>
            @endif
            <p class="mt-4 border-t border-secondary/35 pt-4 text-sm leading-relaxed text-ink/65">
                {{ __('Tell us your dates and interests, we will outline an itinerary that fits this style.') }}
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-secondary/30 bg-white/95 shadow-[0_4px_28px_rgba(46,46,46,0.06)] ring-1 ring-black/[0.03]" aria-labelledby="related-tours-heading">
        <div class="border-b border-secondary/30 bg-gradient-to-r from-primary/[0.06] to-transparent px-4 py-3.5 sm:px-5">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75H15M9 12H15M9 17.25H15M5.25 3.75H18.75C19.9926 3.75 21 4.75736 21 6V18C21 19.2426 19.9926 20.25 18.75 20.25H5.25C4.00736 20.25 3 19.2426 3 18V6C3 4.75736 4.00736 3.75 5.25 3.75Z"/></svg>
                </span>
                <div>
                    <h3 id="related-tours-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">{{ __('Related experiences') }}</h3>
                    <p class="text-xs text-ink/50">{{ __('Other safari, mountain, and Explore Africa options') }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-secondary/15 p-2 sm:p-3">
            <div class="space-y-3 p-1">
                <div>
                    <p class="px-2 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-ink/55">{{ __('Other safari styles') }}</p>
                    <div class="mt-1.5 space-y-1.5">
                        @forelse(($otherSafariStyles ?? collect()) as $style)
                            <a href="{{ route('safari.show', $style) }}" class="block rounded-xl border border-secondary/30 bg-surface/35 px-3 py-2 text-sm font-medium text-ink transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary">
                                {{ $style->title }}
                            </a>
                        @empty
                            <p class="px-2 py-1.5 text-sm text-ink/55">{{ __('No other safari styles yet.') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-secondary/20 pt-3">
                    <p class="px-2 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-ink/55">{{ __('Other mountains') }}</p>
                    <div class="mt-1.5 space-y-1.5">
                        @forelse(($mountainExperiences ?? collect()) as $tour)
                            <a href="{{ route('experiences.show', $tour) }}" class="block rounded-xl border border-secondary/30 bg-surface/35 px-3 py-2 text-sm font-medium text-ink transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary">
                                {{ $tour->title }}
                                @if($tour->mountain)
                                    <span class="block text-xs font-normal text-ink/55">{{ $tour->mountain->name }}</span>
                                @endif
                            </a>
                        @empty
                            <p class="px-2 py-1.5 text-sm text-ink/55">{{ __('No mountain experiences available.') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-secondary/20 pt-3">
                    <p class="px-2 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-ink/55">{{ __('Other Explore Africa') }}</p>
                    <div class="mt-1.5 space-y-1.5">
                        @forelse(($exploreAfricaExperiences ?? collect()) as $tour)
                            <a href="{{ route('experiences.show', $tour) }}" class="block rounded-xl border border-secondary/30 bg-surface/35 px-3 py-2 text-sm font-medium text-ink transition hover:border-primary/30 hover:bg-primary/[0.04] hover:text-primary">
                                {{ $tour->title }}
                                @if($tour->destination)
                                    <span class="block text-xs font-normal text-ink/55">{{ $tour->destination->name }}</span>
                                @endif
                            </a>
                        @empty
                            <p class="px-2 py-1.5 text-sm text-ink/55">{{ __('No Explore Africa experiences available.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-secondary/30 bg-surface/50 px-4 py-3 text-center sm:px-5">
            <a href="{{ route('safari') }}" class="text-sm font-semibold text-primary underline decoration-primary/35 underline-offset-2 transition hover:decoration-primary">{{ __('Browse all safari styles') }}</a>
        </div>
    </div>

    <div class="rounded-2xl border border-secondary/40 bg-gradient-to-br from-surface to-white p-4 shadow-sm sm:p-5">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-ink/45">{{ __('Quick actions') }}</p>
        <ul class="mt-3 space-y-2">
            <li>
                <a href="{{ route('plan-my-safari', ['safari' => $safariExperience->slug]) }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('Plan this safari') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ __('Ask a question') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('explore-africa') }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                        {{ __('Explore Africa') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
        </ul>
    </div>
</div>
