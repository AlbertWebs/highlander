@php
    $socialFacebook = \App\Models\SiteSetting::getValue('social_facebook', '');
    $socialInstagram = \App\Models\SiteSetting::getValue('social_instagram', '');
    $socialTwitter = \App\Models\SiteSetting::getValue('social_twitter', '');
    $socialYoutube = \App\Models\SiteSetting::getValue('social_youtube', '');
    $socialTiktok = \App\Models\SiteSetting::getValue('social_tiktok', '');
    $email = \App\Models\SiteSetting::getValue('contact_email', '');
    $phone = \App\Models\SiteSetting::getValue('contact_phone', '');
    $address = \App\Models\SiteSetting::getValue('contact_address', '');
    $siteHours = \App\Models\SiteSetting::getValue('site_hours', '');
    $footerCredits = trim((string) \App\Models\SiteSetting::getValue('footer_credits', ''));
    $siteLogo = \App\Models\SiteSetting::getValue('site_logo', '');
    $siteLogoDark = \App\Models\SiteSetting::getValue('site_logo_dark', '');
    $footerLogoUrl = \App\Models\SiteSetting::publicUrl($siteLogoDark)
        ?? \App\Models\SiteSetting::publicUrl($siteLogo);

    $socialOk = fn (?string $u): bool => filled($u) && $u !== '#';

    $socialLinks = array_values(array_filter([
        $socialOk($socialFacebook) ? ['href' => $socialFacebook, 'label' => 'Facebook', 'd' => 'M22 12a10 10 0 10-11.5 9.95v-7.05h-2V12h2V9.5c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.2l-.35 2.9h-1.85v7.05A10 10 0 0022 12z'] : null,
        $socialOk($socialInstagram) ? ['href' => $socialInstagram, 'label' => 'Instagram', 'd' => 'M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A4.5 4.5 0 1110.5 12 4.5 4.5 0 0112 7.5zm6.5-.75a1 1 0 11-1 1 1 1 0 011-1z'] : null,
        $socialOk($socialTwitter) ? ['href' => $socialTwitter, 'label' => 'X', 'd' => 'M18.244 2H21l-7.5 8.59L22 22h-6.51l-5.1-6.35L5.5 22H2.8l8.02-9.2L2 2h6.65l4.6 5.78L18.244 2z'] : null,
        $socialOk($socialYoutube) ? ['href' => $socialYoutube, 'label' => 'YouTube', 'd' => 'M23.5 7.2a3 3 0 00-2.1-2.1C19.5 4.5 12 4.5 12 4.5s-7.5 0-9.4.6A3 3 0 00.5 7.2 30 30 0 000 12a30 30 0 00.5 4.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A30 30 0 0024 12a30 30 0 00-.5-4.8zM9.75 15.02v-6l5.5 3-5.5 3z'] : null,
        $socialOk($socialTiktok) ? ['href' => $socialTiktok, 'label' => 'TikTok', 'd' => 'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z'] : null,
    ]));

    $exploreLinks = [
        ['route' => 'home', 'label' => __('Home')],
        ['route' => 'about', 'label' => __('About')],
        ['route' => 'safari', 'label' => __('Safari')],
        ['route' => 'mountains', 'label' => __('Mountains')],
        ['route' => 'explore-africa', 'label' => __('Destinations')],
        ['route' => 'gallery', 'label' => __('Gallery')],
        ['route' => 'articles', 'label' => __('Articles')],
    ];

    $legalLinks = [
        ['route' => 'privacy', 'label' => __('Privacy Policy')],
        ['route' => 'terms', 'label' => __('Terms of Use')],
        ['route' => 'photo-credits', 'label' => __('Photo credits')],
    ];

    $footerFeaturedTours = \App\Models\Tour::query()
        ->active()
        ->featured()
        ->orderBy('sort_order')
        ->take(8)
        ->get();
@endphp
<footer
    class="relative {{ request()->routeIs('home') ? 'mt-0' : 'mt-24' }} overflow-hidden bg-footer text-white section-divider"
    data-aos="fade-up"
    data-aos-duration="850"
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent" aria-hidden="true"></div>
    <div class="relative site-gutter-x py-14 sm:py-16 lg:py-20">
        @include('partials.site-trust-badges')
        <div class="grid gap-12 sm:gap-14 lg:grid-cols-2 xl:grid-cols-4 lg:gap-10 xl:gap-12">
            {{-- Brand --}}
            <div class="lg:max-w-sm">
                @if($footerLogoUrl)
                    <img src="{{ $footerLogoUrl }}" alt="{{ config('app.name') }}" class="mb-5 h-10 w-auto max-w-[200px] object-contain object-left">
                @endif
                <p class="text-base font-semibold tracking-tight text-accent">{{ config('app.name') }}</p>
                <p class="mt-3 text-sm leading-relaxed text-white/80">{{ __('Luxury African journeys crafted with care.') }}</p>
                @if(count($socialLinks))
                    <ul class="mt-6 flex flex-wrap gap-2.5" role="list">
                        @foreach($socialLinks as $s)
                            <li>
                                <a
                                    href="{{ $s['href'] }}"
                                    class="inline-flex rounded-full bg-white/10 p-2.5 text-white transition hover:bg-primary hover:text-white"
                                    aria-label="{{ $s['label'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $s['d'] }}"/></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Explore: two balanced columns on md+ --}}
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-accent/95">{{ __('Explore') }}</p>
                <ul class="mt-5 grid grid-cols-2 gap-x-8 gap-y-2.5 text-sm text-white/85" role="list">
                    @foreach($exploreLinks as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" class="inline-block rounded-sm hover:text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Featured Experiences --}}
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-accent/95">{{ __('Featured Experiences') }}</p>
                <ul class="mt-5 space-y-2.5 text-sm text-white/85" role="list">
                    @forelse($footerFeaturedTours as $tour)
                        <li>
                            <a
                                href="{{ route('experiences.show', $tour) }}"
                                class="inline-block max-w-full rounded-sm leading-snug hover:text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60"
                            >{{ $tour->title }}</a>
                        </li>
                    @empty
                        <li>
                            <a
                                href="{{ route('safari') }}"
                                class="inline-block rounded-sm text-white/70 hover:text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60"
                            >{{ __('Browse safari experiences') }}</a>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-accent/95">{{ __('Contact') }}</p>
                <ul class="mt-5 space-y-3 text-sm text-white/85" role="list">
                    <li>
                        <a href="{{ route('contact') }}" class="inline-block font-medium text-white hover:text-accent focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/60">{{ __('Get in touch') }}</a>
                    </li>
                    @if(filled($email))
                        <li>
                            <a href="mailto:{{ $email }}" class="break-all hover:text-accent">{{ $email }}</a>
                        </li>
                    @endif
                    @if(filled($phone))
                        <li>
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="hover:text-accent">{{ $phone }}</a>
                        </li>
                    @endif
                    @if(filled($address))
                        <li class="whitespace-pre-line text-white/75">{{ $address }}</li>
                    @endif
                    @if(filled($siteHours))
                        <li class="text-white/75">{{ $siteHours }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-14 border-t border-white/15 pt-8">
            <div class="flex flex-col items-stretch gap-6 sm:flex-row sm:items-center sm:justify-between sm:gap-8">
                <p class="footer-credits-line text-center text-xs leading-relaxed text-white/60 sm:text-left [&_a]:font-medium [&_a]:text-white/85 [&_a]:underline [&_a]:underline-offset-2 [&_a]:transition hover:[&_a]:text-accent">
                    @if(filled($footerCredits))
                        {!! $footerCredits !!}
                    @else
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
                    @endif
                </p>
                <nav class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 sm:justify-end" aria-label="{{ __('Legal and credits') }}">
                    @foreach($legalLinks as $link)
                        <a href="{{ route($link['route']) }}" class="text-xs text-white/70 underline-offset-4 transition hover:text-accent hover:underline sm:text-sm">{{ $link['label'] }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
</footer>
