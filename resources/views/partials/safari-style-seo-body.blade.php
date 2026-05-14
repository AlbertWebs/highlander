@php
    /** @var \App\Models\SafariExperience $safariExperience */
    /** @var array<string, mixed> $safariSeo */
@endphp

<div class="seo-safari-style space-y-10">
    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Introduction') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['introduction'] ?? '' !!}</div>
    </section>

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Safari overview') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['overview'] ?? '' !!}</div>
    </section>

    @if(!empty($safariSeo['highlight_cards']))
        <div class="grid gap-4 sm:grid-cols-2" data-aos="fade-up">
            @foreach($safariSeo['highlight_cards'] as $card)
                <div class="rounded-xl border border-secondary/30 bg-gradient-to-br from-white to-surface/90 p-5 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-primary">{{ $card['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/80">{{ $card['body'] }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($safariSeo['quick_facts']))
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4" data-aos="fade-up">
            @foreach($safariSeo['quick_facts'] as $fact)
                <div class="rounded-xl border border-secondary/35 bg-white px-4 py-4 text-center shadow-sm">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-ink/45">{{ $fact['label'] }}</p>
                    <p class="mt-1 text-sm font-semibold text-ink">{{ $fact['value'] }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Pacing & rhythm') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['pacing'] ?? '' !!}</div>
    </section>

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Wildlife experience') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['wildlife'] ?? '' !!}</div>
    </section>

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Lodging & comfort') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['lodging'] ?? '' !!}</div>
    </section>

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Transport & logistics') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['logistics'] ?? '' !!}</div>
    </section>

    <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Best time to visit') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['best_time'] ?? '' !!}</div>
    </section>

    @if(!empty($safariSeo['perfect_for']))
        <div class="rounded-card border border-secondary/30 bg-surface/80 p-6 sm:p-8" data-aos="fade-up">
            <h3 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Perfect for') }}</h3>
            <ul class="mt-4 flex flex-wrap gap-2">
                @foreach($safariSeo['perfect_for'] as $tag)
                    <li class="rounded-full border border-primary/25 bg-primary/[0.06] px-3 py-1.5 text-xs font-medium text-ink/85">{{ $tag }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-card border border-primary/20 bg-gradient-to-br from-white via-surface/90 to-primary/[0.05] p-6 shadow-sm sm:p-8" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Why choose Highlanders Nature Trails') }}</h2>
        <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $safariSeo['why_highlanders'] ?? '' !!}</div>
    </section>

    @if($testimonials && $testimonials->isNotEmpty())
        <section class="rounded-card border border-secondary/35 bg-white p-6 sm:p-8" data-aos="fade-up">
            <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Traveller trust') }}</h2>
            <div class="mt-6 space-y-5">
                @foreach($testimonials as $tm)
                    <figure class="rounded-xl border border-secondary/25 bg-surface/60 p-5">
                        <blockquote class="text-sm leading-relaxed text-ink/85">“{{ $tm->quote }}”</blockquote>
                        <figcaption class="mt-3 text-xs font-semibold uppercase tracking-wide text-ink/55">
                            {{ $tm->name }}
                            @if(filled($tm->country)) · {{ $tm->country }} @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </section>
    @endif

    @if($relatedTours && $relatedTours->isNotEmpty())
        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 sm:p-8" data-aos="fade-up">
            <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Related itineraries') }}</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                @foreach($relatedTours as $rt)
                    <a href="{{ route('experiences.show', $rt) }}" class="group rounded-xl border border-secondary/30 bg-surface/50 p-4 transition hover:border-primary/30 hover:bg-white hover:shadow-md">
                        <h3 class="text-base font-semibold text-ink group-hover:text-primary">{{ $rt->title }}</h3>
                        <span class="mt-2 inline-block text-xs font-semibold text-primary">{{ __('View') }} →</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if(!empty($safariSeo['faq']))
        <section class="rounded-card border border-secondary/40 bg-white p-6 sm:p-8" data-aos="fade-up" x-data="{ open: null }">
            <h2 class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Frequently asked questions') }}</h2>
            <div class="mt-6 divide-y divide-secondary/25 rounded-xl border border-secondary/30">
                @foreach($safariSeo['faq'] as $idx => $row)
                    <div>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between gap-3 px-4 py-4 text-left text-sm font-semibold text-ink transition hover:bg-primary/[0.04]"
                            :aria-expanded="(open === {{ $idx }}) ? 'true' : 'false'"
                            @click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        >
                            <span>{{ $row['question'] }}</span>
                            <svg class="h-4 w-4 shrink-0 text-primary transition-transform" :class="open === {{ $idx }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === {{ $idx }}" x-cloak x-transition class="border-t border-secondary/20 px-4 pb-4 text-sm leading-relaxed text-ink/80">
                            {{ $row['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section id="plan-this-safari-style" class="scroll-mt-28 rounded-card border border-primary/25 bg-gradient-to-br from-primary/10 via-white to-accent/[0.08] p-8 text-center shadow-card sm:p-10" data-aos="fade-up">
        <h2 class="font-serif text-2xl font-semibold text-ink sm:text-3xl">{{ __('Plan this safari style') }}</h2>
        <div class="prose prose-ink prose-site mx-auto mt-4 max-w-3xl text-left">{!! $safariSeo['conclusion'] ?? '' !!}</div>
        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ route('plan-my-safari', ['safari' => $safariExperience->slug]) }}" class="btn-primary inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center bg-gradient-to-r from-primary via-primary to-accent px-8 py-3.5 hover:brightness-110">{{ __('Plan this safari') }}</a>
            <a href="{{ route('contact') }}" class="btn-secondary inline-flex min-h-[3rem] min-w-[10rem] items-center justify-center px-8 py-3.5">{{ __('Speak with a designer') }}</a>
        </div>
    </section>
</div>
