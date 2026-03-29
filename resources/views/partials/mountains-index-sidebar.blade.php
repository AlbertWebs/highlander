@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Tour> $featuredTours */
    $tourFallbackImg = 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=400&q=80';
@endphp

<div class="space-y-6 lg:sticky lg:top-24 lg:z-10 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto lg:overscroll-y-contain lg:pr-1 xl:top-28 xl:max-h-[calc(100vh-7rem)]">
    <div class="rounded-2xl border border-secondary/40 bg-gradient-to-br from-surface to-white p-4 shadow-sm sm:p-5">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-ink/45">{{ __('Plan your trek') }}</p>
        <ul class="mt-3 space-y-2">
            <li>
                <a href="{{ route('plan-my-safari') }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ __('Plan a safari') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ __('Ask about trekking') }}
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
            <li>
                <a href="{{ route('safari') }}" class="flex items-center justify-between gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm font-medium text-ink shadow-sm ring-1 ring-secondary/40 transition hover:border-primary/25 hover:ring-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 20h12M6 16h12M4 6h16M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2"/></svg>
                        {{ __('Safari experiences') }}
                    </span>
                    <svg class="h-4 w-4 shrink-0 text-ink/35" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </li>
        </ul>
    </div>

    <div class="rounded-2xl border border-secondary/35 bg-white/95 shadow-soft ring-1 ring-black/[0.03]" aria-labelledby="mountains-sidebar-tours-heading">
        <div class="border-b border-secondary/30 bg-gradient-to-r from-primary/[0.06] to-transparent px-4 py-3.5 sm:px-5">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75H15M9 12H15M9 17.25H15M5.25 3.75H18.75C19.9926 3.75 21 4.75736 21 6V18C21 19.2426 19.9926 20.25 18.75 20.25H5.25C4.00736 20.25 3 19.2426 3 18V6C3 4.75736 4.00736 3.75 5.25 3.75Z"/></svg>
                </span>
                <div>
                    <h2 id="mountains-sidebar-tours-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">{{ __('Featured itineraries') }}</h2>
                    <p class="text-xs text-ink/50">{{ __('Combine peaks with parks & coast') }}</p>
                </div>
            </div>
        </div>
        <div class="divide-y divide-secondary/25 p-3 sm:p-4">
            @forelse($featuredTours as $tour)
                <a
                    href="{{ route('experiences.show', $tour) }}"
                    class="group flex gap-3 rounded-xl p-2.5 transition first:pt-1 last:pb-1 hover:bg-primary/[0.04]"
                >
                    <div class="relative h-[4.5rem] w-[5.5rem] shrink-0 overflow-hidden rounded-xl bg-secondary/40 ring-1 ring-secondary/50">
                        @if($tour->image)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->image) }}"
                                alt=""
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                loading="lazy"
                                width="88"
                                height="72"
                            >
                        @else
                            <img src="{{ $tourFallbackImg }}" alt="" class="h-full w-full object-cover opacity-90" loading="lazy">
                        @endif
                        @if($tour->is_featured)
                            <span class="absolute left-1 top-1 rounded bg-primary/95 px-1.5 py-0.5 text-[0.6rem] font-bold uppercase tracking-wide text-white shadow-sm">{{ __('Featured') }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 py-0.5">
                        <p class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition group-hover:text-primary">{{ $tour->title }}</p>
                        <p class="mt-1 flex flex-wrap items-center gap-x-2 text-xs text-ink/55">
                            @if($tour->duration_days)
                                <span>{{ trans_choice(':count day|:count days', $tour->duration_days, ['count' => $tour->duration_days]) }}</span>
                            @endif
                            @if($tour->price)
                                @if($tour->duration_days)<span class="text-ink/30" aria-hidden="true">·</span>@endif
                                <span>{{ __('From') }} ${{ number_format((float) $tour->price, 0) }}</span>
                            @endif
                        </p>
                        <span class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-primary sm:mt-2 sm:opacity-0 sm:transition sm:group-hover:opacity-100">
                            {{ __('View') }}
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @empty
                <p class="px-2 py-4 text-center text-sm text-ink/60">{{ __('Itineraries coming soon.') }}</p>
            @endforelse
        </div>
        <div class="border-t border-secondary/30 bg-surface/50 px-4 py-3 text-center sm:px-5">
            <a href="{{ route('safari') }}" class="text-sm font-semibold text-primary underline decoration-primary/35 underline-offset-2 transition hover:decoration-primary">{{ __('See all experiences') }}</a>
        </div>
    </div>

    <div class="rounded-2xl border border-primary/15 bg-gradient-to-br from-primary/[0.07] to-tint-green/30 p-5 sm:p-6">
        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Trekking tip') }}</p>
        <p class="mt-2 text-sm leading-relaxed text-ink/75">
            {{ __('Open any peak for full detail, then use “Plan safari” to send dates—we will suggest acclimatisation and rest days.') }}
        </p>
    </div>
</div>
