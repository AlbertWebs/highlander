@php
    /** @var \App\Models\Tour $tour */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $relatedTours */
    $tourFallbackImg = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=400&q=80';
@endphp

<div class="space-y-6 lg:sticky lg:top-24 lg:z-10 xl:top-28">
    @if($tour->duration_days || $tour->price)
        <div class="overflow-hidden rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/[0.08] via-white to-tint-green/35 shadow-card ring-1 ring-primary/10" aria-labelledby="experience-glance-heading">
            <div class="border-b border-primary/10 bg-primary/[0.06] px-5 py-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-primary/15" aria-hidden="true">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5"/></svg>
                    </span>
                    <div>
                        <p id="experience-glance-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">{{ __('At a glance') }}</p>
                        <p class="mt-0.5 text-xs text-ink/55">{{ __('Duration & indicative pricing') }}</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4 px-5 py-5">
                @if($tour->duration_days)
                    <p class="font-serif text-2xl font-semibold tabular-nums tracking-tight text-ink sm:text-3xl">
                        {{ trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]) }}
                    </p>
                @endif
                @if($tour->price)
                    <p class="@if($tour->duration_days) border-t border-secondary/35 pt-4 @endif text-sm leading-relaxed text-ink/65">
                        <span class="font-semibold text-ink">{{ __('From') }} ${{ number_format((float) $tour->price, 0) }}</span>
                        {{ __('per person, depending on season and group size.') }}
                    </p>
                @endif
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-secondary/35 bg-white/95 shadow-soft ring-1 ring-black/[0.03]" aria-labelledby="related-tours-heading">
        <div class="border-b border-secondary/30 bg-gradient-to-r from-primary/[0.06] to-transparent px-4 py-3.5 sm:px-5">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75H15M9 12H15M9 17.25H15M5.25 3.75H18.75C19.9926 3.75 21 4.75736 21 6V18C21 19.2426 19.9926 20.25 18.75 20.25H5.25C4.00736 20.25 3 19.2426 3 18V6C3 4.75736 4.00736 3.75 5.25 3.75Z"/></svg>
                </span>
                <div>
                    <h3 id="related-tours-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">{{ __('Related experiences') }}</h3>
                    <p class="text-xs text-ink/50">{{ __('More itineraries you might like') }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-secondary/25 p-3 sm:p-4">
            @forelse($relatedTours as $rt)
                <a
                    href="{{ route('experiences.show', $rt) }}"
                    class="group flex gap-3 rounded-xl p-2.5 transition first:pt-1 last:pb-1 hover:bg-primary/[0.04]"
                >
                    <div class="relative h-[4.5rem] w-[5.5rem] shrink-0 overflow-hidden rounded-xl bg-secondary/40 ring-1 ring-secondary/50">
                        @if($rt->image)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($rt->image) }}"
                                alt=""
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                loading="lazy"
                                width="88"
                                height="72"
                            >
                        @else
                            <img src="{{ $tourFallbackImg }}" alt="" class="h-full w-full object-cover opacity-90" loading="lazy">
                        @endif
                        @if($rt->is_featured)
                            <span class="absolute left-1 top-1 rounded bg-primary/95 px-1.5 py-0.5 text-[0.6rem] font-bold uppercase tracking-wide text-white shadow-sm">{{ __('Top pick') }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 py-0.5">
                        <p class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition group-hover:text-primary">{{ $rt->title }}</p>
                        <p class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-ink/55">
                            @if($rt->duration_days)
                                <span>{{ trans_choice(':count day|:count days', $rt->duration_days, ['count' => $rt->duration_days]) }}</span>
                            @endif
                            @if($rt->price)
                                @if($rt->duration_days)<span class="text-ink/30" aria-hidden="true">·</span>@endif
                                <span>{{ __('From') }} ${{ number_format((float) $rt->price, 0) }}</span>
                            @endif
                        </p>
                        <span class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-primary sm:mt-2 sm:opacity-0 sm:transition sm:group-hover:opacity-100">
                            {{ __('View itinerary') }}
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @empty
                <p class="px-2 py-4 text-center text-sm text-ink/60">{{ __('Experiences will appear here as we add them.') }}</p>
            @endforelse
        </div>
        <div class="border-t border-secondary/30 bg-surface/50 px-4 py-3 text-center sm:px-5">
            <a href="{{ route('safari') }}" class="text-sm font-semibold text-primary underline decoration-primary/35 underline-offset-2 transition hover:decoration-primary">{{ __('Browse all safari styles') }}</a>
        </div>
    </div>

    <div class="rounded-2xl border border-secondary/40 bg-gradient-to-br from-surface to-white p-4 shadow-sm sm:p-5">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-ink/45">{{ __('Quick actions') }}</p>
        <ul class="mt-3 space-y-2">
            <li>
                <a href="{{ route('plan-my-safari', ['tour' => $tour->slug]) }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
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
                <a href="{{ route('home') }}#featured-experiences-heading" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        {{ __('More experiences') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
        </ul>
    </div>
</div>
