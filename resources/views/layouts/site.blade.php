<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @include('partials.favicon-links')
    @stack('meta')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:ital,wght@0,400;0,500;0,600;0,700|figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface font-sans text-ink antialiased">
    <div
        id="site-preloader"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-surface transition-opacity duration-500"
        role="status"
        aria-live="polite"
        aria-label="{{ __('Loading website') }}"
    >
        <div class="flex flex-col items-center gap-4">
            <div class="h-12 w-12 animate-spin rounded-full border-4 border-secondary/35 border-t-primary"></div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-ink/65">{{ __('Loading') }}</p>
        </div>
    </div>

    @include('partials.site-header')
    <main>
        @yield('content')
    </main>
    @include('partials.site-footer')
    @include('partials.scroll-to-top')
    @stack('scripts')
    <script>
        (function () {
            const preloader = document.getElementById('site-preloader');
            if (!preloader) return;

            const hidePreloader = () => {
                if (preloader.dataset.hidden === '1') return;
                preloader.dataset.hidden = '1';
                preloader.classList.add('opacity-0', 'pointer-events-none');
                window.setTimeout(() => preloader.remove(), 550);
            };

            const essentialReady = () => {
                const fontsReady = document.fonts?.ready ?? Promise.resolve();
                Promise.resolve(fontsReady).finally(hidePreloader);
            };

            if (document.readyState === 'complete') {
                essentialReady();
            } else {
                window.addEventListener('load', essentialReady, { once: true });
            }

            window.setTimeout(hidePreloader, 5000);
        })();
    </script>
</body>
</html>
