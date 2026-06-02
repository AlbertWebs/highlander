<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')). — {{ config('app.name') }}</title>
    @include('partials.favicon-links')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
</head>
<body class="admin-layout min-h-screen bg-surface font-sans text-ink antialiased lg:h-[100dvh] lg:overflow-hidden">
<div
    x-data="{
        sidebarOpen: true,
        mobileNav: false,
        closeMobileNav() { this.mobileNav = false; },
    }"
    @keydown.escape.window="mobileNav = false"
    @resize.window="if (window.innerWidth >= 1024) { mobileNav = false; }"
    class="flex min-h-screen lg:h-[100dvh] lg:max-h-[100dvh] lg:min-h-0 lg:overflow-hidden"
>
    <div
        x-show="mobileNav"
        x-cloak
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileNav = false"
        class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        aria-hidden="true"
    ></div>

    @include('admin.partials.sidebar')

    <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:min-h-0 lg:overflow-hidden">
        <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center justify-between gap-3 border-b border-secondary/40 bg-white/90 px-4 backdrop-blur lg:static lg:z-30 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-secondary/50 bg-surface text-ink transition hover:border-primary/30 hover:text-primary lg:hidden"
                    @click="mobileNav = true"
                    :aria-expanded="mobileNav"
                    aria-controls="admin-sidebar"
                    aria-label="{{ __('Open menu') }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="truncate text-lg font-semibold text-ink">@yield('heading')</h1>
                    <nav class="text-xs text-ink/60" aria-label="Breadcrumb">
                        @yield('breadcrumb')
                    </nav>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ request()->url() }}" method="get" class="hidden rounded-xl border border-secondary/50 bg-surface px-3 py-1.5 md:flex">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search') }}…" class="w-48 bg-transparent text-sm focus:outline-none">
                </form>
                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-full border border-secondary/50 bg-surface px-3 py-1.5 text-sm">
                        <span class="h-8 w-8 rounded-full bg-primary/20 text-center leading-8 text-primary">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-52 rounded-xl border border-secondary/50 bg-white py-1 shadow-card" style="display:none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-secondary/40">{{ __('Profile') }}</a>
                        <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 text-sm hover:bg-secondary/40">{{ __('Settings') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-secondary/40">{{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <div class="admin-main-scroll flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-4 lg:p-8">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
<script>
    (function () {
        function removeStuckEditorOverlays() {
            document.querySelectorAll('.tox-dialog-wrap, .tox-dialog-wrap__backdrop').forEach(function (el) {
                var dialog = el.querySelector('.tox-dialog');
                if (!dialog || dialog.getAttribute('aria-hidden') === 'true') {
                    el.remove();
                }
            });
            document.body.classList.remove('tox-dialog__disable-scroll');
            document.body.style.removeProperty('overflow');
        }

        document.addEventListener('DOMContentLoaded', removeStuckEditorOverlays);
        window.addEventListener('pageshow', removeStuckEditorOverlays);
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                removeStuckEditorOverlays();
            }
        });
    })();
</script>
</body>
</html>
