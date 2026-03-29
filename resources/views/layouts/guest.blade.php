<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} — {{ $pageTitle ?? __('Admin sign in') }}</title>
        @include('partials.favicon-links')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface font-sans text-ink antialiased">
        @php
            $gl = \App\Models\SiteSetting::getValue('site_logo', '');
            $gd = \App\Models\SiteSetting::getValue('site_logo_dark', '');
            $guestLogo = \App\Models\SiteSetting::publicUrl($gl) ?? \App\Models\SiteSetting::publicUrl($gd);
        @endphp
        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10 sm:px-6 sm:py-14">
            <a href="{{ route('home') }}" class="group mb-8 flex flex-col items-center gap-3 rounded-2xl px-4 py-2 text-center transition hover:opacity-90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface">
                @if($guestLogo)
                    <img src="{{ $guestLogo }}" alt="{{ config('app.name') }}" class="h-12 w-auto max-w-[220px] object-contain drop-shadow-sm transition group-hover:scale-[1.02] sm:h-14 sm:max-w-[260px]">
                @else
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/12 text-xl font-serif font-semibold text-primary ring-1 ring-primary/25">{{ \Illuminate\Support\Str::substr(config('app.name'), 0, 1) }}</span>
                    <span class="font-serif text-lg font-semibold tracking-tight text-primary sm:text-xl">{{ config('app.name') }}</span>
                @endif
                <span class="text-xs font-medium text-ink/55">{{ __('Back to site') }}</span>
            </a>
            <div class="w-full max-w-[420px] rounded-2xl border border-secondary/45 bg-white/95 p-8 shadow-card ring-1 ring-primary/5 backdrop-blur-sm sm:p-10">
                {{ $slot }}
            </div>
            <p class="mt-8 max-w-sm text-center text-xs leading-relaxed text-ink/50">{{ __('Administrator access only. If you are a guest planning a trip, please use the contact options on our website.') }}</p>
        </div>
    </body>
</html>
