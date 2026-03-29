@php
    $nav = [
        ['label' => __('Home'), 'route' => 'home'],
        ['label' => __('About'), 'route' => 'about'],
        ['label' => __('Mountains'), 'route' => 'mountains'],
        ['label' => __('Explore Africa'), 'route' => 'explore-africa'],
        ['label' => __('Safari'), 'route' => 'safari'],
        ['label' => __('Gallery'), 'route' => 'gallery'],
        ['label' => __('Articles'), 'route' => 'articles'],
    ];
    $contactEmail = \App\Models\SiteSetting::getValue('contact_email', '');
    $siteHours = \App\Models\SiteSetting::getValue('site_hours', '');
    $siteLogo = \App\Models\SiteSetting::getValue('site_logo', '');
    $siteLogoDark = \App\Models\SiteSetting::getValue('site_logo_dark', '');
    /** Light / bright backgrounds (default logo) vs dark hero overlay (logo_dark). */
    $logoLightBgUrl = \App\Models\SiteSetting::publicUrl($siteLogo);
    $logoDarkBgUrl = \App\Models\SiteSetting::publicUrl($siteLogoDark);
    $headerLogoUrlHero = $logoDarkBgUrl ?: $logoLightBgUrl;
    $headerLogoUrlStuck = $logoLightBgUrl ?: $logoDarkBgUrl;
    $menuBgUrl = \App\Models\SiteSetting::publicUrl(\App\Models\SiteSetting::getValue('menu_background_image', ''));
    $appName = config('app.name', 'Highlander');
@endphp
<header
    x-data="{ open: false, scrolled: false }"
    x-init="(() => { const sync = () => { scrolled = window.scrollY > 24 }; sync(); window.addEventListener('scroll', sync, { passive: true }) })()"
    :class="scrolled
        ? 'border-neutral-200/90 shadow-[0_10px_36px_rgba(46,46,46,0.08)] ring-1 ring-black/[0.04]'
        : 'border-white/15 shadow-[0_4px_24px_rgba(0,0,0,0.2)]'"
    class="fixed inset-x-0 top-0 z-50 flex flex-col border-b border-white/10 bg-transparent transition-[border-color,box-shadow] duration-500 ease-out"
>
    {{-- Top utility bar --}}
    <div
        class="relative z-[2] hidden overflow-hidden border-b border-white/10 transition-all duration-300 ease-out sm:block"
        :class="scrolled ? 'max-h-0 border-b-0 opacity-0 pointer-events-none' : 'max-h-[12rem] opacity-100'"
    >
        @if($menuBgUrl)
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-cover bg-center bg-no-repeat sm:bg-[center_top]" style="background-image: url('{{ $menuBgUrl }}')"></div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 transition-colors duration-500" :class="scrolled ? 'bg-black/75' : 'bg-black/55'"></div>
        @endif
        <div @class([
            'site-gutter-x relative z-[1] flex w-full items-center justify-between gap-4 py-2 text-[11px] font-medium tracking-wide text-white',
            'bg-black/40' => ! $menuBgUrl,
            'bg-black/25 backdrop-blur-[2px]' => (bool) $menuBgUrl,
        ])>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                <a href="{{ route('explore-africa') }}" class="text-white transition hover:text-white/90">{{ __('Explore Africa') }}</a>
                <span class="text-white/35" aria-hidden="true">|</span>
                <a href="{{ route('about') }}" class="text-white transition hover:text-white/90">{{ __('About us') }}</a>
            </div>
            <div class="flex flex-wrap items-center justify-end gap-x-5 gap-y-1 text-white">
                @if($siteHours !== '')
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 shrink-0 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $siteHours }}
                    </span>
                @endif
                @if($contactEmail !== '')
                    <a href="mailto:{{ $contactEmail }}" class="inline-flex items-center gap-1.5 text-white transition hover:text-white/90">
                        <svg class="h-3.5 w-3.5 shrink-0 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $contactEmail }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Sticky bar --}}
    <div
        class="flex flex-col transition-[background-color] duration-300 ease-out"
        :class="scrolled ? 'site-header-stuck bg-surface shadow-[0_8px_28px_rgba(46,46,46,0.06)]' : 'bg-transparent'"
    >
        <div
            class="pointer-events-none h-px w-full shrink-0 bg-gradient-to-r from-transparent to-transparent"
            :class="scrolled ? 'via-neutral-300/50' : 'via-white/35'"
            aria-hidden="true"
        ></div>

        {{-- Main row: brand | centered nav | sharp CTAs (reference layout) --}}
        <div class="site-gutter-x relative z-[2] flex w-full shrink-0 items-center gap-4 py-3.5 lg:gap-8">
            <a
                href="{{ route('home') }}"
                class="site-header-brand group relative z-20 flex min-w-0 shrink-0 items-center gap-3 text-white transition duration-300 hover:opacity-95 md:gap-4"
            >
                @if($headerLogoUrlHero || $headerLogoUrlStuck)
                    <img
                        src="{{ $headerLogoUrlHero }}"
                        width="200"
                        height="40"
                        alt="{{ $appName }}"
                        class="h-9 w-auto max-w-[min(200px,38vw)] object-contain object-left transition-opacity duration-300 lg:h-10"
                        x-bind:src="scrolled ? @js($headerLogoUrlStuck) : @js($headerLogoUrlHero)"
                    />
                    <span
                        class="site-header-brand-rule hidden h-9 w-px shrink-0 md:block lg:h-10"
                        :class="scrolled ? 'bg-neutral-400/55' : 'bg-white/35'"
                        aria-hidden="true"
                    ></span>
                    <span class="hidden flex-col justify-center leading-none md:flex">
                        <span class="site-header-brand-tagline text-[0.625rem] font-semibold uppercase tracking-[0.2em] text-white/95">{{ __('Safaris') }}</span>
                        <span class="site-header-brand-tagline mt-1 text-[0.625rem] font-semibold uppercase tracking-[0.2em] text-white/95">{{ __('& expeditions') }}</span>
                    </span>
                @else
                    <span class="font-serif text-xl font-semibold tracking-tight text-white drop-shadow-md sm:text-2xl lg:text-[1.65rem]">{{ $appName }}</span>
                    <span
                        class="site-header-brand-rule hidden h-9 w-px shrink-0 sm:block lg:h-10"
                        :class="scrolled ? 'bg-neutral-400/55' : 'bg-white/35'"
                        aria-hidden="true"
                    ></span>
                    <span class="hidden flex-col justify-center leading-none sm:flex">
                        <span class="site-header-brand-tagline text-[0.625rem] font-semibold uppercase tracking-[0.2em] text-white/95">{{ __('Safaris') }}</span>
                        <span class="site-header-brand-tagline mt-1 text-[0.625rem] font-semibold uppercase tracking-[0.2em] text-white/95">{{ __('& expeditions') }}</span>
                    </span>
                @endif
            </a>

            <nav class="site-primary-nav hidden min-w-0 flex-1 items-center justify-center gap-x-4 md:flex lg:gap-x-6 xl:gap-x-7" aria-label="{{ __('Primary') }}">
                @foreach($nav as $item)
                    @php
                        $navActive = request()->routeIs($item['route']);
                        $letter = mb_strtoupper(mb_substr($item['label'], 0, 1));
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'site-nav-link inline-flex items-center gap-2 whitespace-nowrap text-[0.625rem] font-semibold uppercase tracking-[0.2em] text-white/95 transition hover:opacity-85',
                            'site-nav-link--active' => $navActive,
                        ])
                    >
                        @if($navActive)
                            <span class="site-nav-letter inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-white/90 text-[0.625rem] font-semibold leading-none text-white" aria-hidden="true">{{ $letter }}</span>
                        @endif
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="site-header-ctas relative z-20 hidden shrink-0 items-center gap-2 md:flex md:gap-2.5">
                <a
                    href="{{ route('safari') }}"
                    class="site-header-ctas-btn-primary inline-flex items-center justify-center rounded-none border-0 bg-primary px-5 py-2.5 text-center text-xs font-semibold uppercase tracking-[0.12em] text-white shadow-none transition hover:bg-primary/90"
                >{{ __('Explore safaris') }}</a>
                <a
                    href="{{ route('contact') }}"
                    class="site-header-ctas-btn-secondary inline-flex items-center justify-center rounded-none border-0 bg-white px-5 py-2.5 text-center text-xs font-semibold uppercase tracking-[0.12em] text-ink shadow-none transition hover:bg-white/95"
                >{{ __('Get in touch') }}</a>
            </div>

            <div class="ml-auto flex items-center md:ml-0 md:hidden">
                <button
                    type="button"
                    class="inline-flex rounded-lg border border-white/20 bg-white/5 p-2.5 text-white shadow-sm backdrop-blur-sm transition duration-300 hover:border-white/45 hover:bg-white/15"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-controls="mobile-nav"
                >
                    <span class="sr-only">{{ __('Menu') }}</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div
            id="mobile-nav"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="site-mobile-nav-panel site-gutter-x relative z-[2] py-5 md:hidden"
            :class="scrolled
                ? 'border-t border-neutral-200/80 bg-surface shadow-[inset_0_1px_0_rgba(0,0,0,0.04)]'
                : 'border-t border-white/15 bg-black/45 shadow-[inset_0_1px_0_rgba(255,255,255,0.06)] backdrop-blur-2xl'"
            style="display: none;"
        >
            <div
                class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent to-transparent"
                :class="scrolled ? 'via-neutral-300/45' : 'via-white/40'"
                aria-hidden="true"
            ></div>
            <div class="relative flex flex-col gap-1">
                @foreach($nav as $item)
                    @php
                        $mActive = request()->routeIs($item['route']);
                        $mLetter = mb_strtoupper(mb_substr($item['label'], 0, 1));
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'site-nav-link-mobile flex items-center justify-center gap-2 px-4 py-3 text-[0.625rem] font-semibold uppercase tracking-[0.2em] transition duration-200 ease-out',
                            'bg-white/10 text-white' => $mActive,
                            'text-white/95 hover:bg-white/10' => ! $mActive,
                        ])
                        @click="open = false"
                    >
                        @if($mActive)
                            <span class="site-nav-letter inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-white/90 text-[0.625rem] font-semibold text-white" aria-hidden="true">{{ $mLetter }}</span>
                        @endif
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <div class="mt-3 flex flex-col gap-2 border-t border-white/10 pt-4">
                    <a href="{{ route('safari') }}" class="site-header-ctas-btn-primary inline-flex justify-center rounded-none bg-primary px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white hover:bg-primary/90" @click="open = false">{{ __('Explore safaris') }}</a>
                    <a href="{{ route('contact') }}" class="site-header-ctas-btn-secondary inline-flex justify-center rounded-none bg-white px-4 py-3 text-sm font-semibold uppercase tracking-wide text-ink hover:bg-white/95" @click="open = false">{{ __('Get in touch') }}</a>
                </div>
            </div>
        </div>
    </div>
</header>
