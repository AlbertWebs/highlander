<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="light">
        <title>{{ config('app.name') }} - {{ $pageTitle ?? __('Admin sign in') }}</title>
        @include('partials.favicon-links')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cormorant-garamond:ital,wght@0,500;0,600;0,700|figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface font-sans text-ink antialiased">
        @php
            $gl = \App\Models\SiteSetting::getValue('site_logo', '');
            $gd = \App\Models\SiteSetting::getValue('site_logo_dark', '');
            $guestLogo = \App\Models\SiteSetting::publicUrl($gl) ?? \App\Models\SiteSetting::publicUrl($gd);
        @endphp
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-1/4 top-0 h-[min(70vh,32rem)] w-[min(70vw,42rem)] rounded-full bg-primary/[0.07] blur-3xl"></div>
            <div class="absolute -right-1/4 bottom-0 h-[min(60vh,28rem)] w-[min(65vw,36rem)] rounded-full bg-accent/[0.09] blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-10%,rgba(76,175,80,0.12),transparent_55%)]"></div>
        </div>

        <div class="relative z-10 flex min-h-screen flex-col lg:grid lg:min-h-0 lg:grid-cols-[minmax(280px,38%)_minmax(0,1fr)]">
            {{-- Brand column: desktop only --}}
            <aside class="relative hidden min-h-screen flex-col justify-center border-r border-secondary/40 bg-gradient-to-br from-tint-green/90 via-white to-surface px-10 py-16 xl:px-14 xl:py-20 lg:flex">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/25 to-transparent" aria-hidden="true"></div>
                <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-primary/15 to-transparent" aria-hidden="true"></div>
                <p class="pointer-events-none absolute bottom-10 left-10 font-serif text-[5.5rem] leading-none text-primary/[0.07] xl:left-14" aria-hidden="true">&ldquo;</p>
                <div class="relative max-w-md">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ __('Team access') }}</p>
                    <h2 class="mt-5 font-serif text-[1.85rem] font-semibold leading-[1.15] tracking-tight text-ink sm:text-[2.15rem]">
                        {{ __('Shape the story travellers see before they ever pack a bag.') }}
                    </h2>
                    <p class="mt-5 text-sm leading-relaxed text-ink/70">
                        {{ __('Tours, galleries, articles, and enquiries are managed from here. Open the dashboard when you are ready to work on the live site.') }}
                    </p>
                    <a
                        href="{{ route('home') }}"
                        class="mt-10 inline-flex items-center gap-2 text-sm font-medium text-primary underline decoration-primary/30 underline-offset-4 transition hover:decoration-primary"
                    >
                        <svg class="h-4 w-4 shrink-0 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                        {{ __('Back to website') }}
                    </a>
                </div>
            </aside>

            {{-- Form column --}}
            <div class="flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-6 sm:py-12 lg:min-h-screen lg:px-10 lg:py-16 xl:px-14">
                <div class="mb-8 max-w-md text-center lg:hidden sm:mb-10">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-primary">{{ __('Team access') }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink/65">{{ __('Manage tours, galleries, articles, and enquiries for the live site.') }}</p>
                </div>

                <a
                    href="{{ route('home') }}"
                    class="group mb-8 flex max-w-md flex-col items-center gap-2 rounded-2xl px-4 py-2 text-center transition hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-transparent sm:mb-10 lg:mb-10"
                >
                    @if($guestLogo)
                        <img src="{{ $guestLogo }}" alt="{{ config('app.name') }}" class="h-11 w-auto max-w-[200px] object-contain drop-shadow-sm transition duration-300 group-hover:scale-[1.02] sm:h-12 sm:max-w-[240px]">
                    @else
                        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/12 text-xl font-serif font-semibold text-primary ring-1 ring-primary/20 sm:text-2xl">{{ \Illuminate\Support\Str::substr(config('app.name'), 0, 1) }}</span>
                        <span class="font-serif text-lg font-semibold tracking-tight text-primary sm:text-xl">{{ config('app.name') }}</span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-primary/90 underline decoration-primary/25 underline-offset-4 transition group-hover:decoration-primary lg:hidden">
                        <svg class="h-3.5 w-3.5 shrink-0 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                        {{ __('Back to website') }}
                    </span>
                </a>

                <div class="w-full max-w-[440px] overflow-hidden rounded-[1.35rem] border border-secondary/50 bg-white/95 shadow-depth ring-1 ring-black/[0.04] backdrop-blur-md sm:rounded-3xl">
                    <div class="h-1 bg-gradient-to-r from-primary/80 via-accent/90 to-primary/70" aria-hidden="true"></div>
                    <div class="px-6 pb-8 pt-7 sm:px-9 sm:pb-10 sm:pt-8">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-8 max-w-[26rem] text-pretty text-center text-[0.8125rem] leading-relaxed text-ink/50 sm:mt-8">
                    {{ __('Administrator access only. Travellers planning a trip should use Contact or Plan My Safari on the public site.') }}
                </p>
            </div>
        </div>
    </body>
</html>
