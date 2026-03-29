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
    $testimonialSlides = $aboutTestimonials->isEmpty() ? collect() : $aboutTestimonials->chunk(3);
@endphp

@section('content')
@include('partials.about-hero', ['setting' => $setting])

{{-- Company introduction --}}
<section class="section-y bg-white section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-aos="fade-up" data-aos-duration="800">
                <h2 class="mb-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                    {{ $setting->intro_heading }}
                </h2>
                <p class="mb-6 text-base leading-relaxed text-ink/85">{{ $setting->intro_paragraph_1 }}</p>
                @if(filled($setting->intro_paragraph_2))
                    <p class="mb-8 text-base leading-relaxed text-ink/80">{{ $setting->intro_paragraph_2 }}</p>
                @endif
                <a href="{{ route('plan-my-safari') }}" class="btn-primary px-8 py-3.5">{{ $introCta }}</a>
            </div>
            <div class="img-zoom-parent overflow-hidden rounded-card shadow-depth lg:justify-self-end" data-aos="fade-up" data-aos-duration="850" data-aos-delay="80">
                <img src="{{ $introImg }}" alt="" class="img-zoom-hover aspect-[4/3] w-full max-w-xl object-cover lg:ml-auto" loading="lazy" width="1200" height="900">
            </div>
        </div>
    </div>
</section>

{{-- Vision, Mission, Promise --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid gap-6 md:grid-cols-3 md:gap-8">
            @foreach($visionCards as $card)
                <article
                    class="card-depth flex flex-col p-8 text-center sm:p-9"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="{{ min(200, 80 * $loop->index) }}"
                >
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary/15 to-accent/20 text-3xl shadow-inner ring-1 ring-primary/10" aria-hidden="true">{{ $card->icon }}</span>
                    <h3 class="mt-5 font-serif text-xl font-semibold text-primary">{{ $card->title }}</h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-ink/80">{{ $card->body }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Core values --}}
<section class="section-y bg-white section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <h2 class="mb-10 text-center font-serif text-3xl font-semibold text-ink sm:mb-12 sm:text-4xl" data-aos="fade-up" data-aos-duration="800">
            {{ $setting->core_values_section_title }}
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            @foreach($coreValues as $val)
                <article
                    class="card-depth p-6 sm:p-7"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="{{ min(300, 50 * $loop->index) }}"
                >
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-2xl" aria-hidden="true">{{ $val->icon }}</span>
                    <h3 class="mt-4 font-serif text-lg font-semibold text-primary">{{ $val->title }}</h3>
                    @if(filled($val->description))
                        <p class="mt-2 text-sm leading-relaxed text-ink/75">{{ $val->description }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Fleet & equipment --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
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
                <p class="mb-8 text-base leading-relaxed text-ink/85">{{ $setting->fleet_body }}</p>
                <ul class="space-y-6">
                    @foreach($fleetSubsections as $sub)
                        <li class="border-l-4 border-primary/50 pl-5">
                            <h3 class="font-semibold text-primary">{{ $sub->title }}</h3>
                            @if(filled($sub->body))
                                <p class="mt-2 text-sm leading-relaxed text-ink/80">{{ $sub->body }}</p>
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
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="img-zoom-parent order-2 overflow-hidden rounded-card shadow-depth lg:order-1" data-aos="fade-up" data-aos-duration="800">
                <img src="{{ $teamImg }}" alt="" class="img-zoom-hover aspect-[4/3] w-full object-cover" loading="lazy" width="1200" height="900">
            </div>
            <div class="order-1 lg:order-2" data-aos="fade-up" data-aos-duration="850">
                <h2 class="mb-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">{{ $setting->team_heading }}</h2>
                <p class="mb-8 text-base leading-relaxed text-ink/85">{{ $setting->team_body }}</p>
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
    <div class="site-gutter-x mx-auto max-w-7xl">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-aos="fade-up" data-aos-duration="800">
                <h2 class="mb-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">{{ $setting->safety_heading }}</h2>
                <p class="mb-8 text-base leading-relaxed text-ink/85">{{ $setting->safety_body }}</p>
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

{{-- Sustainability --}}
<section class="section-y bg-white section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
        <h2 class="mb-10 text-center font-serif text-3xl font-semibold text-ink sm:mb-12 sm:text-4xl" data-aos="fade-up" data-aos-duration="800">
            {{ $setting->sustainability_section_title }}
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($sustainabilityItems as $item)
                <article
                    class="card-depth p-6 sm:p-7"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="{{ min(250, 60 * $loop->index) }}"
                >
                    <span class="text-3xl" aria-hidden="true">{{ $item->icon }}</span>
                    <h3 class="mt-4 font-serif text-lg font-semibold text-primary">{{ $item->title }}</h3>
                    @if(filled($item->description))
                        <p class="mt-2 text-sm leading-relaxed text-ink/75">{{ $item->description }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="section-y bg-surface section-divider">
    <div class="site-gutter-x mx-auto max-w-7xl">
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
                                    <div class="grid gap-6 md:grid-cols-3 md:gap-8">
                                        @foreach($slide as $t)
                                            @php
                                                $rating = min(5, max(1, (int) ($t->rating ?? 5)));
                                                $initials = \Illuminate\Support\Str::of($t->name)->explode(' ')->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                                            @endphp
                                            <blockquote class="card-depth flex min-h-[260px] flex-col bg-white p-6 sm:min-h-[280px] sm:p-8">
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

{{-- CTA --}}
<section class="relative overflow-hidden section-y bg-gradient-to-br from-primary via-primary to-[#2E7D32] section-divider text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_120%,rgba(0,0,0,0.18),transparent_55%)]" aria-hidden="true"></div>
    <div class="site-gutter-x relative mx-auto max-w-3xl text-center">
        <h2 class="mb-4 font-serif text-3xl font-semibold sm:text-4xl" data-aos="fade-up" data-aos-duration="800">{{ $setting->cta_heading }}</h2>
        <p class="mb-8 text-lg text-white/95" data-aos="fade-up" data-aos-duration="800" data-aos-delay="60">{{ $setting->cta_body }}</p>
        <a
            href="{{ route('plan-my-safari') }}"
            class="inline-flex min-h-[3rem] items-center justify-center rounded-btn bg-white px-10 py-3.5 text-base font-semibold text-primary shadow-lg transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-white/95 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-primary"
            data-aos="fade-up"
            data-aos-duration="800"
            data-aos-delay="120"
        >{{ $ctaLabel }}</a>
    </div>
</section>
@endsection
