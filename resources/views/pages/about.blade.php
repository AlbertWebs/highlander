@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('About Us').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@php
    $introImg = \App\Models\SiteSetting::resolvePublicAssetUrl($setting->intro_image)
        ?? 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80';
    $teamImg = \App\Models\SiteSetting::resolvePublicAssetUrl($setting->team_image)
        ?? 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80';
    $safetyImg = \App\Models\SiteSetting::resolvePublicAssetUrl($setting->safety_image)
        ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1200&q=80';
    $introCta = filled($setting->intro_cta_label) ? $setting->intro_cta_label : __('Plan My Safari');
    $ctaLabel = filled($setting->cta_button_label) ? $setting->cta_button_label : __('Plan My Safari');
    $testimonialSlides = $aboutTestimonials->isEmpty() ? collect() : $aboutTestimonials->chunk(2);
@endphp

@section('content')
@include('partials.about-hero', ['setting' => $setting])

{{-- Company introduction --}}
<section class="section-y bg-white section-divider">
    <div class="site-gutter-x w-full">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-aos="fade-up" data-aos-duration="800">
                <h2 class="mb-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                    {{ $setting->intro_heading }}
                </h2>
                <div class="about-rich-text mb-6 text-base leading-relaxed text-ink/85">{!! $setting->intro_paragraph_1 !!}</div>
                @if(filled($setting->intro_paragraph_2))
                    <div class="about-rich-text mb-8 text-base leading-relaxed text-ink/80">{!! $setting->intro_paragraph_2 !!}</div>
                @endif
                <a href="{{ route('plan-my-safari') }}" class="btn-primary px-8 py-3.5">{{ $introCta }}</a>
            </div>
            <div class="img-zoom-parent overflow-hidden rounded-card shadow-depth lg:justify-self-end" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
                <img src="{{ $introImg }}" alt="" class="img-zoom-hover aspect-[4/3] w-full object-cover" loading="lazy" width="1200" height="900">
            </div>
        </div>
    </div>
</section>

{{-- Vision, Mission, Promise — editorial layout (no icons) --}}
@php
    $visionList = $visionCards->values();
    $visionCount = $visionList->count();
@endphp
@if($visionCount > 0)
    <section class="section-y relative overflow-hidden bg-gradient-to-b from-surface via-white to-surface section-divider" aria-labelledby="about-vision-mission-heading">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/25 to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-32 top-1/4 h-96 w-96 rounded-full bg-primary/[0.06] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-72 w-72 rounded-full bg-accent/[0.08] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute left-1/2 top-[38%] h-px w-full max-w-none -translate-x-1/2 bg-gradient-to-r from-transparent via-secondary/35 to-transparent opacity-60" aria-hidden="true"></div>

        <div class="site-gutter-x relative w-full">
            <div class="text-center" data-aos="fade-up" data-aos-duration="750">
                <div class="flex items-center justify-center gap-4 sm:gap-5">
                    <span class="h-px w-10 bg-gradient-to-r from-transparent to-primary/40 sm:w-14" aria-hidden="true"></span>
                    <p id="about-vision-mission-heading" class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ __('Our compass') }}</p>
                    <span class="h-px w-10 bg-gradient-to-l from-transparent to-primary/40 sm:w-14" aria-hidden="true"></span>
                </div>
                <p class="mt-4 w-full text-sm leading-relaxed text-ink/65 sm:mt-5 sm:text-base">{{ __('The principles that shape how we guide, host, and care for every journey.') }}</p>
            </div>

            @if($visionCount === 1)
                @php $card = $visionList->first(); @endphp
                <div class="mt-16 w-full" data-aos="fade-up" data-aos-duration="850">
                    @include('partials.about-vision-mission-statement', ['card' => $card, 'first' => null])
                </div>
            @elseif($visionCount === 2)
                <div class="mt-16 grid gap-8 md:grid-cols-2 md:gap-10 lg:gap-12">
                    @foreach($visionList as $card)
                        @include('partials.about-vision-mission-column', ['card' => $card, 'index' => $loop->index])
                    @endforeach
                </div>
            @else
                <div class="mt-16 grid gap-8 lg:grid-cols-2 lg:gap-x-10 xl:gap-x-12">
                    @foreach($visionList->take(2) as $card)
                        @include('partials.about-vision-mission-column', ['card' => $card, 'index' => $loop->index])
                    @endforeach
                </div>
                @foreach($visionList->slice(2) as $card)
                    @include('partials.about-vision-mission-statement', ['card' => $card, 'first' => $loop->first])
                @endforeach
            @endif
        </div>
    </section>
@endif

{{-- Core values — editorial cards, no icons --}}
@if($coreValues->isNotEmpty())
    <section class="section-y relative overflow-hidden bg-gradient-to-b from-white via-surface/40 to-white section-divider" aria-labelledby="about-core-values-heading">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-20 top-1/3 h-64 w-64 rounded-full bg-primary/[0.05] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-16 bottom-1/4 h-56 w-56 rounded-full bg-accent/[0.06] blur-3xl" aria-hidden="true"></div>

        <div class="site-gutter-x relative w-full">
            <div class="text-center" data-aos="fade-up" data-aos-duration="750">
                <div class="flex items-center justify-center gap-4 sm:gap-5">
                    <span class="h-px w-10 bg-gradient-to-r from-transparent to-primary/40 sm:w-14" aria-hidden="true"></span>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ __('What we stand for') }}</p>
                    <span class="h-px w-10 bg-gradient-to-l from-transparent to-primary/40 sm:w-14" aria-hidden="true"></span>
                </div>
                <h2 id="about-core-values-heading" class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:mt-5 sm:text-4xl">
                    {{ $setting->core_values_section_title }}
                </h2>
                <p class="mt-4 w-full text-sm leading-relaxed text-ink/65 sm:mt-5 sm:text-base">{{ __('The beliefs behind every itinerary and every guest we welcome.') }}</p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-7 xl:gap-8">
                @foreach($coreValues as $val)
                    @include('partials.about-core-value', ['val' => $val, 'index' => $loop->index])
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- Fleet & equipment --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x w-full">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="grid grid-cols-2 gap-3 sm:gap-4" data-aos="fade-up" data-aos-duration="800">
                @foreach($fleetImages as $fi)
                    <div @class([
                        'img-zoom-parent overflow-hidden rounded-card shadow-depth',
                        'col-span-2' => $loop->first,
                    ])>
                        @if($fi->url())
                            <img src="{{ $fi->url() }}" alt="{{ $fi->caption ?? '' }}" class="img-zoom-hover aspect-[4/3] h-full w-full object-cover" loading="lazy">
                        @else
                            <div class="flex aspect-[4/3] items-center justify-center bg-gradient-to-br from-primary/20 to-secondary/40 text-sm font-medium text-ink/50">
                                {{ $fi->caption ?? __('Photo') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div data-aos="fade-up" data-aos-duration="850" data-aos-delay="60">
                <h2 class="mb-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">{{ $setting->fleet_heading }}</h2>
                <div class="about-rich-text mb-8 text-base leading-relaxed text-ink/85">{!! $setting->fleet_body !!}</div>
                <ul class="space-y-6">
                    @foreach($fleetSubsections as $sub)
                        <li class="border-l-4 border-primary/50 pl-5">
                            <h3 class="font-semibold text-primary">{{ $sub->title }}</h3>
                            @if(filled($sub->body))
                                <div class="about-rich-text about-rich-text--sm mt-2 text-sm leading-relaxed text-ink/80">{!! $sub->body !!}</div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Team --}}
<section class="section-y bg-white section-divider">
    <div class="site-gutter-x w-full">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="img-zoom-parent order-2 overflow-hidden rounded-card shadow-depth lg:order-1" data-aos="fade-up" data-aos-duration="800">
                <img src="{{ $teamImg }}" alt="" class="img-zoom-hover aspect-[4/3] w-full object-cover" loading="lazy" width="1200" height="900">
            </div>
            <div class="order-1 lg:order-2" data-aos="fade-up" data-aos-duration="850">
                <h2 class="mb-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">{{ $setting->team_heading }}</h2>
                <div class="about-rich-text mb-8 text-base leading-relaxed text-ink/85">{!! $setting->team_body !!}</div>
                <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2" role="list">
                    @foreach($teamRoles as $role)
                        <li class="flex items-center gap-2 rounded-xl border border-secondary/40 bg-surface/80 px-4 py-3 text-sm font-medium text-ink">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-accent" aria-hidden="true"></span>
                            {{ $role->label }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Safety --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x w-full">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-aos="fade-up" data-aos-duration="800">
                <h2 class="mb-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">{{ $setting->safety_heading }}</h2>
                <div class="about-rich-text mb-8 text-base leading-relaxed text-ink/85">{!! $setting->safety_body !!}</div>
                <ul class="space-y-3" role="list">
                    @foreach($safetyPoints as $pt)
                        <li class="flex gap-3 text-sm leading-relaxed text-ink/90">
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-bold text-primary" aria-hidden="true">✓</span>
                            {{ $pt->point_text }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="img-zoom-parent overflow-hidden rounded-card shadow-depth" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
                <img src="{{ $safetyImg }}" alt="" class="img-zoom-hover aspect-[4/3] w-full object-cover" loading="lazy" width="1200" height="900">
            </div>
        </div>
    </div>
</section>

{{-- Sustainability — editorial cards, no icons --}}
@if($sustainabilityItems->isNotEmpty())
    <section class="section-y relative overflow-hidden bg-gradient-to-b from-surface/80 via-white to-primary/[0.04] section-divider" aria-labelledby="about-sustainability-heading">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-accent/25 to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-0 top-1/4 h-72 w-72 translate-x-1/4 rounded-full bg-primary/[0.06] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-accent/[0.07] blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute left-1/2 top-[42%] h-px w-full max-w-none -translate-x-1/2 bg-gradient-to-r from-transparent via-secondary/30 to-transparent opacity-50" aria-hidden="true"></div>

        <div class="site-gutter-x relative w-full">
            <div class="text-center" data-aos="fade-up" data-aos-duration="750">
                <div class="flex items-center justify-center gap-4 sm:gap-5">
                    <span class="h-px w-10 bg-gradient-to-r from-transparent to-accent/45 sm:w-14" aria-hidden="true"></span>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ __('Travel that gives back') }}</p>
                    <span class="h-px w-10 bg-gradient-to-l from-transparent to-accent/45 sm:w-14" aria-hidden="true"></span>
                </div>
                <h2 id="about-sustainability-heading" class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:mt-5 sm:text-4xl">
                    {{ $setting->sustainability_section_title }}
                </h2>
                <p class="mt-4 w-full text-sm leading-relaxed text-ink/65 sm:mt-5 sm:text-base">{{ __('Conservation, communities, and culture—woven into how we plan, guide, and grow.') }}</p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-7 xl:gap-8">
                @foreach($sustainabilityItems as $item)
                    @include('partials.about-sustainability-item', ['item' => $item, 'index' => $loop->index])
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- Testimonials --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x w-full">
        <h2 class="mb-8 text-center font-serif text-3xl font-semibold text-primary sm:text-4xl" data-aos="fade-up" data-aos-duration="800">
            {{ $setting->testimonials_section_title }}
        </h2>
        @if($aboutTestimonials->isEmpty())
            <p class="text-center text-ink/60">{{ __('Testimonials will appear here. Mark testimonials as “Show on About” in the admin, or add new reviews.') }}</p>
        @else
            <div
                class="rounded-xl outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                x-data="testimonialCarousel({ total: {{ $testimonialSlides->count() }} })"
                role="region"
                tabindex="0"
                aria-roledescription="{{ __('carousel') }}"
                aria-label="{{ __('Testimonials') }}"
                @keydown.left.prevent="prev()"
                @keydown.right.prevent="next()"
            >
                <div class="flex items-stretch gap-2 sm:gap-4">
                    <button
                        type="button"
                        class="hidden h-11 w-11 shrink-0 self-center rounded-full border border-secondary/50 bg-white p-0 text-primary shadow-depth transition duration-300 ease-out hover:border-primary/30 hover:shadow-depth-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:flex sm:items-center sm:justify-center"
                        x-show="total > 1"
                        x-cloak
                        @click="prev()"
                        aria-label="{{ __('Previous testimonials') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    </button>
                    <div class="min-w-0 flex-1 overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-out motion-reduce:transition-none motion-reduce:duration-0" :style="`transform: translateX(-${current * 100}%)`">
                            @foreach($testimonialSlides as $slide)
                                <div class="min-w-full shrink-0 px-0.5 sm:px-1">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-6 lg:gap-8">
                                        @foreach($slide as $t)
                                            @php
                                                $rating = min(5, max(1, (int) ($t->rating ?? 5)));
                                                $initials = \Illuminate\Support\Str::of($t->name)->explode(' ')->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                                                $soloInSlide = $slide->count() === 1;
                                            @endphp
                                            <blockquote @class([
                                                'card-depth flex min-h-[240px] flex-col bg-white p-6 sm:min-h-[260px] sm:p-8',
                                                'sm:col-span-2 sm:max-w-xl sm:justify-self-center md:max-w-2xl' => $soloInSlide,
                                            ])>
                                                <div class="flex flex-col gap-4 sm:flex-row sm:gap-5">
                                                    @if($t->image)
                                                        <img src="{{ $t->imageUrl() }}" alt="" class="mx-auto h-14 w-14 shrink-0 rounded-full object-cover ring-2 ring-primary/15 sm:mx-0 sm:h-16 sm:w-16" loading="lazy">
                                                    @else
                                                        <div class="mx-auto flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-primary/10 font-serif text-lg font-semibold text-primary ring-2 ring-primary/10 sm:mx-0 sm:h-16 sm:w-16 sm:text-xl" aria-hidden="true">{{ $initials }}</div>
                                                    @endif
                                                    <div class="min-w-0 flex-1 text-center sm:text-left">
                                                        <div class="flex justify-center gap-0.5 sm:justify-start" role="img" aria-label="{{ $rating }}/5">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <span class="text-base leading-none {{ $i <= $rating ? 'text-amber-500' : 'text-ink/15' }}" aria-hidden="true">★</span>
                                                            @endfor
                                                        </div>
                                                        <p class="mt-3 text-sm leading-relaxed text-ink/90 sm:text-base">“{{ $t->quote }}”</p>
                                                        <footer class="mt-4 border-t border-secondary/30 pt-4">
                                                            <cite class="not-italic font-semibold text-primary">{{ $t->name }}</cite>
                                                            @if(filled($t->country))
                                                                <p class="text-sm text-ink/65">{{ $t->country }}</p>
                                                            @elseif(filled($t->role))
                                                                <p class="text-sm text-ink/65">{{ $t->role }}</p>
                                                            @endif
                                                        </footer>
                                                    </div>
                                                </div>
                                            </blockquote>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button
                        type="button"
                        class="hidden h-11 w-11 shrink-0 self-center rounded-full border border-secondary/50 bg-white p-0 text-primary shadow-depth transition duration-300 ease-out hover:border-primary/30 hover:shadow-depth-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:flex sm:items-center sm:justify-center"
                        x-show="total > 1"
                        x-cloak
                        @click="next()"
                        aria-label="{{ __('Next testimonials') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5 15.75 12l-7.5 7.5" /></svg>
                    </button>
                </div>
                @if($testimonialSlides->count() > 1)
                    <div class="mt-8 flex justify-center gap-2" role="tablist" aria-label="{{ __('Testimonial slides') }}">
                        @foreach($testimonialSlides as $slide)
                            <button
                                type="button"
                                class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary motion-reduce:transition-none"
                                :class="current === {{ $loop->index }} ? 'w-8 bg-primary' : 'w-2 bg-secondary/40'"
                                @click="goTo({{ $loop->index }})"
                                :aria-selected="current === {{ $loop->index }}"
                                aria-label="{{ __('Slide') }} {{ $loop->iteration }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- CTA — same treatment as home hero CTA; footer uses mt-0 on this route so block meets footer --}}
<section class="relative overflow-hidden bg-gradient-to-br from-primary via-primary to-[#2E7D32] section-y text-white section-divider" aria-labelledby="about-cta-heading">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_120%,rgba(0,0,0,0.2),transparent_55%)]" aria-hidden="true"></div>
    <div class="absolute inset-0 opacity-25" style="background-image: url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;"></div>
    <div class="site-gutter-x relative w-full text-center" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
        <h2 id="about-cta-heading" class="mb-4 font-serif text-3xl font-semibold tracking-tight sm:text-4xl lg:text-[2.75rem]">{{ $setting->cta_heading }}</h2>
        <div class="about-rich-text about-rich-text--light mx-auto mb-8 w-full max-w-none text-lg leading-relaxed">{!! $setting->cta_body !!}</div>
        <a
            href="{{ route('plan-my-safari') }}"
            class="inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center rounded-btn bg-white px-10 py-3.5 text-center text-base font-semibold text-primary shadow-lg transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-white/95 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary"
        >{{ $ctaLabel }}</a>
    </div>
</section>
@endsection
