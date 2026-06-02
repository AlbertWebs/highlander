@php
    /** @var \App\Models\Mountain $mountain */
    /** @var \Illuminate\Support\Collection<int, \App\Models\SafariExperience> $mountainSafaris */
@endphp

<section class="mt-12 border-t border-primary/15 pt-12" aria-labelledby="mountain-safaris-heading">
    <div class="flex flex-col gap-5 border-b border-secondary/25 pb-6 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0 max-w-3xl">
            <p class="inline-flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">
                <span class="h-1 w-6 rounded-full bg-gradient-to-r from-primary to-accent" aria-hidden="true"></span>
                {{ __('Safari styles') }}
            </p>
            <h2 id="mountain-safaris-heading" class="mt-3 font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.875rem] lg:text-[2rem]">
                <span class="bg-gradient-to-r from-primary via-primary to-accent bg-clip-text text-transparent">
                    {{ __('Safaris for :mountain', ['mountain' => $mountain->name]) }}
                </span>
            </h2>
            <p class="mt-3 text-sm leading-relaxed text-ink/70 sm:text-[0.9375rem]">
                @if($mountainSafaris->isNotEmpty())
                    {{ trans_choice(
                        ':count safari style linked to this peak in admin—open any card for the full introduction and itineraries.|:count safari styles linked to this peak in admin—open any card for the full introduction and itineraries.',
                        $mountainSafaris->count(),
                        ['count' => $mountainSafaris->count()]
                    ) }}
                @else
                    {{ __('Link safari styles to this mountain in Admin → Safari (Mountain field) to list them here.') }}
                @endif
            </p>
        </div>
        <a
            href="{{ route('safari') }}"
            class="btn-primary inline-flex shrink-0 items-center gap-2 self-start px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.12em] sm:self-end"
        >
            {{ __('All safari styles') }}
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($mountainSafaris->isNotEmpty())
        <nav class="mt-8 rounded-2xl border border-secondary/30 bg-gradient-to-br from-white via-surface/50 to-primary/[0.04] p-5 shadow-sm sm:p-6" aria-labelledby="mountain-safaris-index-heading">
            <h3 id="mountain-safaris-index-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-primary">
                {{ __('On this page') }}
            </h3>
            <ol class="mt-4 grid gap-x-8 gap-y-2 sm:grid-cols-2">
                @foreach($mountainSafaris as $safari)
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

        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-7 lg:grid-cols-3 lg:gap-8">
            @foreach($mountainSafaris as $safari)
                @include('partials.featured-safari-card', [
                    'safari' => $safari,
                    'cardIndex' => $loop->index,
                ])
            @endforeach
        </div>
    @else
        <div class="mt-8 rounded-2xl border border-dashed border-secondary/50 bg-surface/70 px-6 py-10 text-center">
            <p class="text-base font-medium text-ink">{{ __('No safari styles linked to this mountain yet.') }}</p>
            <p class="mx-auto mt-2 max-w-lg text-sm text-ink/60">{{ __('In Admin → Safari, choose this mountain on each safari style that should appear here.') }}</p>
        </div>
    @endif
</section>
