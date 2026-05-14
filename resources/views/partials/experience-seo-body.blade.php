@if($zone === 'upper')
    <div class="seo-safari-article">
        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-intro-heading" data-aos="fade-up">
            <h2 id="seo-intro-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Introduction') }}</h2>
            <div class="prose prose-ink prose-headings:font-serif prose-site mt-2 max-w-none">{!! $tourSeo['introduction'] ?? '' !!}</div>
        </section>

        <section class="mt-10 rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-overview-heading" data-aos="fade-up" data-aos-delay="40">
            <h2 id="seo-overview-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Safari overview') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['overview'] ?? '' !!}</div>
        </section>

        <section class="mt-10 rounded-card border border-primary/20 bg-gradient-to-br from-primary/[0.06] via-white to-tint-green/20 p-6 shadow-sm sm:p-8" aria-labelledby="seo-highlights-heading" data-aos="fade-up" data-aos-delay="80">
            <h2 id="seo-highlights-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Highlights') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['highlights'] ?? '' !!}</div>
            @if(!empty($tourSeo['highlight_cards']))
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach($tourSeo['highlight_cards'] as $card)
                        <div class="rounded-xl border border-secondary/30 bg-white/90 p-5 shadow-sm transition hover:border-primary/25 hover:shadow-md" data-aos="fade-up" data-aos-delay="{{ min(160, 40 + $loop->index * 40) }}">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-primary">{{ $card['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-ink/80">{{ $card['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        @if(!empty($tourSeo['quick_facts']))
            <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4" data-aos="fade-up">
                @foreach($tourSeo['quick_facts'] as $fact)
                    <div class="rounded-xl border border-secondary/35 bg-white px-4 py-4 text-center shadow-sm">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-ink/45">{{ $fact['label'] }}</p>
                        <p class="mt-1 text-sm font-semibold text-ink">{{ $fact['value'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($tourSeo['perfect_for']))
            <div class="mt-10 rounded-card border border-secondary/30 bg-surface/80 p-6 sm:p-8" data-aos="fade-up">
                <h3 class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Perfect for') }}</h3>
                <ul class="mt-4 flex flex-wrap gap-2">
                    @foreach($tourSeo['perfect_for'] as $tag)
                        <li class="rounded-full border border-primary/25 bg-primary/[0.06] px-3 py-1.5 text-xs font-medium text-ink/85">{{ $tag }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="mt-10 rounded-card border border-secondary/25 bg-white/90 p-6 sm:p-8" aria-labelledby="seo-itinerary-preface-heading" data-aos="fade-up">
            <h2 id="seo-itinerary-preface-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Detailed itinerary') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['itinerary_intro'] ?? '' !!}</div>
        </section>
    </div>
@else
    <div class="seo-safari-lower space-y-10">
        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-wildlife-heading" data-aos="fade-up">
            <h2 id="seo-wildlife-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Wildlife experience') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['wildlife'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-accommodation-heading" data-aos="fade-up">
            <h2 id="seo-accommodation-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Accommodation experience') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['accommodation'] ?? '' !!}</div>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['evenings'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-logistics-heading" data-aos="fade-up">
            <h2 id="seo-logistics-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Transport & logistics') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['logistics'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-best-time-heading" data-aos="fade-up">
            <h2 id="seo-best-time-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Best time to visit') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['best_time'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-packing-heading" data-aos="fade-up">
            <h2 id="seo-packing-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('What to carry') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['packing'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-conservation-heading" data-aos="fade-up">
            <h2 id="seo-conservation-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Conservation & community') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['conservation'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-primary/20 bg-gradient-to-br from-white via-surface/90 to-primary/[0.05] p-6 shadow-sm sm:p-8" aria-labelledby="seo-why-heading" data-aos="fade-up">
            <h2 id="seo-why-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Why choose Highlanders Nature Trails') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['why_highlanders'] ?? '' !!}</div>
        </section>

        <section class="rounded-card border border-secondary/30 bg-white/95 p-6 shadow-sm sm:p-8" aria-labelledby="seo-destination-heading" data-aos="fade-up">
            <h2 id="seo-destination-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Destination overview') }}</h2>
            <div class="prose prose-ink prose-site mt-2 max-w-none">{!! $tourSeo['destination_block'] ?? '' !!}</div>
        </section>

        @if($testimonials && $testimonials->isNotEmpty())
            <section class="rounded-card border border-secondary/35 bg-white p-6 sm:p-8" aria-labelledby="seo-trust-heading" data-aos="fade-up">
                <h2 id="seo-trust-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Traveller trust') }}</h2>
                <div class="mt-6 space-y-5">
                    @foreach($testimonials as $tm)
                        <figure class="rounded-xl border border-secondary/25 bg-surface/60 p-5">
                            <blockquote class="text-sm leading-relaxed text-ink/85">“{{ $tm->quote }}”</blockquote>
                            <figcaption class="mt-3 text-xs font-semibold uppercase tracking-wide text-ink/55">
                                {{ $tm->name }}
                                @if(filled($tm->country)) · {{ $tm->country }} @endif
                                @if($tm->rating)
                                    <span class="text-primary"> · {{ str_repeat('★', min(5, (int) $tm->rating)) }}</span>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        @if($relatedTours && $relatedTours->isNotEmpty())
            <section class="rounded-card border border-secondary/30 bg-white/95 p-6 sm:p-8" aria-labelledby="seo-related-heading" data-aos="fade-up">
                <h2 id="seo-related-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Related safaris & experiences') }}</h2>
                <p class="mt-2 text-sm text-ink/60">{{ __('Internal links help you compare pacing, regions, and budget bands, without losing the thread of what makes each journey distinct.') }}</p>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    @foreach($relatedTours as $rt)
                        <a href="{{ route('experiences.show', $rt) }}" class="group/card relative flex flex-col overflow-hidden rounded-2xl border border-secondary/25 bg-gradient-to-b from-white to-surface/80 p-5 shadow-[0_4px_22px_rgba(46,46,46,0.06)] ring-1 ring-black/[0.02] transition duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:shadow-[0_14px_36px_rgba(46,46,46,0.1)]">
                            <h3 class="pr-8 font-serif text-lg font-semibold leading-snug text-ink transition group-hover/card:text-primary">{{ $rt->title }}</h3>
                            @if($rt->duration_days)
                                <p class="mt-3 inline-flex w-fit rounded-full border border-primary/20 bg-primary/[0.07] px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.12em] text-primary">
                                    {{ trans_choice(':count day|:count days', $rt->duration_days, ['count' => $rt->duration_days]) }}
                                </p>
                            @endif
                            <span class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold text-primary">
                                {{ __('View itinerary') }}
                                <span aria-hidden="true" class="transition group-hover/card:translate-x-0.5">→</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(!empty($tourSeo['faq']))
            <section class="rounded-card border border-secondary/40 bg-white p-6 sm:p-8" aria-labelledby="seo-faq-heading" data-aos="fade-up" x-data="{ open: null }">
                <h2 id="seo-faq-heading" class="font-serif text-2xl font-semibold text-primary sm:text-[1.75rem]">{{ __('Frequently asked questions') }}</h2>
                <div class="mt-6 divide-y divide-secondary/25 rounded-xl border border-secondary/30">
                    @foreach($tourSeo['faq'] as $idx => $row)
                        <div class="faq-item">
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

        <section id="plan-this-experience" class="scroll-mt-28 rounded-card border border-primary/25 bg-gradient-to-br from-primary/10 via-white to-accent/[0.08] p-8 text-center shadow-card sm:p-10" aria-labelledby="seo-conclusion-heading" data-aos="fade-up">
            <h2 id="seo-conclusion-heading" class="font-serif text-2xl font-semibold text-ink sm:text-3xl">{{ __('Plan this journey') }}</h2>
            <div class="prose prose-ink prose-site mx-auto mt-4 max-w-3xl text-left">{!! $tourSeo['conclusion'] ?? '' !!}</div>
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ route('plan-my-safari', ['tour' => $tour->slug]) }}" class="btn-primary inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center bg-gradient-to-r from-primary via-primary to-accent px-8 py-3.5 hover:brightness-110">{{ __('Plan this safari') }}</a>
                <a href="{{ route('contact') }}" class="btn-secondary inline-flex min-h-[3rem] min-w-[10rem] items-center justify-center px-8 py-3.5">{{ __('Speak with a designer') }}</a>
            </div>
        </section>
    </div>
@endif
