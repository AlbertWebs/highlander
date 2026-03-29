@extends('layouts.site')



@section('title', filled($meta_title ?? null) ? $meta_title : __('Contact').' — '.config('app.name'))



@push('meta')

    @include('partials.seo-meta')

@endpush



@section('content')

@include('partials.page-hero', ['title' => __('Contact'), 'subtitle' => __('We respond within one business day.')])



<section class="site-gutter-x mx-auto max-w-6xl section-y-compact bg-white section-divider" data-reveal>

    @if(session('success'))

        <div class="mb-8 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('success') }}</div>

    @endif



    <div class="grid gap-12 lg:grid-cols-12 lg:gap-14">

        <div class="space-y-8 lg:col-span-5">

            <div>

                <h2 class="text-lg font-semibold text-ink">{{ __('Get in touch') }}</h2>

                <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ __('Reach us by email, phone, or the form. We aim to reply within one business day.') }}</p>

            </div>



            <ul class="space-y-4">

                @if(filled($contactEmail))

                    <li class="flex gap-4 rounded-2xl border border-secondary/50 bg-surface p-5 shadow-sm">

                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>

                        </span>

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">{{ __('Email') }}</p>

                            <a href="mailto:{{ $contactEmail }}" class="mt-1 block break-all text-sm font-medium text-primary hover:underline">{{ $contactEmail }}</a>

                        </div>

                    </li>

                @endif

                @if(filled($contactPhone))

                    <li class="flex gap-4 rounded-2xl border border-secondary/50 bg-surface p-5 shadow-sm">

                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>

                        </span>

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">{{ __('Phone') }}</p>

                            <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}" class="mt-1 block text-sm font-medium text-primary hover:underline">{{ $contactPhone }}</a>

                        </div>

                    </li>

                @endif

                @if(filled($contactAddress))

                    <li class="flex gap-4 rounded-2xl border border-secondary/50 bg-surface p-5 shadow-sm">

                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>

                        </span>

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">{{ __('Address') }}</p>

                            <p class="mt-1 whitespace-pre-line text-sm text-ink/85">{{ $contactAddress }}</p>

                        </div>

                    </li>

                @endif

                @if(filled($siteHours))

                    <li class="flex gap-4 rounded-2xl border border-secondary/50 bg-surface p-5 shadow-sm">

                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>

                        </span>

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">{{ __('Hours') }}</p>

                            <p class="mt-1 text-sm text-ink/85">{{ $siteHours }}</p>

                        </div>

                    </li>

                @endif

            </ul>



            @php

                $socials = array_filter([

                    ['href' => $socialFacebook, 'label' => 'Facebook'],

                    ['href' => $socialInstagram, 'label' => 'Instagram'],

                    ['href' => $socialYoutube, 'label' => 'YouTube'],

                    ['href' => $socialTwitter, 'label' => 'X'],

                    ['href' => $socialTiktok, 'label' => 'TikTok'],

                ], fn ($s) => filled($s['href']));

            @endphp

            @if(count($socials))

                <div class="rounded-2xl border border-secondary/50 bg-surface p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">{{ __('Social') }}</p>

                    <ul class="mt-3 flex flex-wrap gap-3">

                        @foreach($socials as $s)

                            <li>

                                <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-lg border border-secondary/60 bg-white px-3 py-2 text-xs font-medium text-ink/80 transition hover:border-primary/40 hover:text-primary">{{ $s['label'] }}</a>

                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

        </div>



        <div class="lg:col-span-7">

            <form action="{{ route('contact.store') }}" method="post" x-data="{ booking: {{ old('booking_intent') ? 'true' : 'false' }} }" class="relative space-y-6 rounded-2xl border border-secondary/50 bg-surface p-8 shadow-card" data-aos="fade-up" data-aos-duration="850" data-aos-delay="100">

                @csrf

                {{-- Honeypot: leave empty; bots often fill hidden fields --}}

                <div class="pointer-events-none absolute -left-[9999px] h-px w-px overflow-hidden opacity-0" aria-hidden="true">

                    <label for="hp_website">{{ __('Company website') }}</label>

                    <input type="text" name="hp_website" id="hp_website" value="" tabindex="-1" autocomplete="off">

                </div>



                <h2 class="text-lg font-semibold text-ink">{{ __('Send a message') }}</h2>



                <div class="grid gap-6 sm:grid-cols-2">

                    <div>

                        <label class="block text-sm font-medium text-ink">{{ __('Name') }}</label>

                        <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 text-ink shadow-sm focus:border-primary focus:ring-primary">

                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-ink">{{ __('Email') }}</label>

                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                    </div>

                </div>

                <div>

                    <label class="block text-sm font-medium text-ink">{{ __('Phone') }}</label>

                    <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                </div>

                <div class="flex items-center gap-3 rounded-xl bg-secondary/30 px-4 py-3">

                    <input type="checkbox" name="booking_intent" value="1" id="booking_intent" x-model="booking" class="rounded border-secondary text-primary focus:ring-primary">

                    <label for="booking_intent" class="text-sm text-ink">{{ __('I want to request a booking / itinerary') }}</label>

                </div>

                <div x-show="booking" x-cloak class="grid gap-6 sm:grid-cols-2">

                    <div>

                        <label class="block text-sm font-medium text-ink">{{ __('Tour (optional)') }}</label>

                        <select name="tour_id" class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                            <option value="">—</option>

                            @foreach($tours as $t)

                                <option value="{{ $t->id }}" @selected(old('tour_id') == $t->id)>{{ $t->title }}</option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-ink">{{ __('Preferred start') }}</label>

                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-ink">{{ __('Guests') }}</label>

                        <input type="number" name="guests" min="1" value="{{ old('guests', 2) }}" class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                    </div>

                </div>

                <div>

                    <label class="block text-sm font-medium text-ink">{{ __('Subject') }}</label>

                    <input name="subject" value="{{ old('subject') }}" class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">

                </div>

                <div>

                    <label class="block text-sm font-medium text-ink">{{ __('Message') }}</label>

                    <textarea name="message" rows="6" required class="mt-1 w-full rounded-xl border-secondary/60 bg-white px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old('message') }}</textarea>

                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                </div>

                <button type="submit" class="w-full rounded-xl bg-primary py-3 font-semibold text-white shadow-soft transition hover:bg-primary/90">{{ __('Send message') }}</button>

            </form>

        </div>

    </div>

</section>



<section class="site-gutter-x mx-auto max-w-6xl pb-20" data-aos="fade-up" data-aos-duration="850">

    <h2 class="text-lg font-semibold text-ink">{{ __('Find us') }}</h2>

    <p class="mt-2 max-w-2xl text-sm text-ink/65">{{ __('Map location for our office or a central meeting point. Update the embed URL under Admin → Settings if needed.') }}</p>

    <div class="mt-6 overflow-hidden rounded-2xl border border-secondary/50 bg-secondary/20 shadow-card">

        @if(filled($contactMapEmbedUrl))

            <iframe

                title="{{ __('Map') }}"

                src="{{ $contactMapEmbedUrl }}"

                class="h-[min(420px,55vh)] w-full border-0"

                loading="lazy"

                referrerpolicy="no-referrer-when-downgrade"

                allowfullscreen

            ></iframe>

        @else

            <div class="flex min-h-[280px] flex-col items-center justify-center gap-3 px-6 py-16 text-center">

                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-ink/35 shadow-sm" aria-hidden="true">

                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>

                </span>

                <p class="max-w-md text-sm text-ink/60">{{ __('No map URL is configured yet. Add a map embed link in the admin settings to show your location here.') }}</p>

            </div>

        @endif

    </div>

</section>

@endsection

