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
<body class="min-h-screen bg-surface font-sans text-ink antialiased lg:h-[100dvh] lg:overflow-hidden">
<div x-data="{ sidebarOpen: true, mobileNav: false }" class="flex min-h-screen lg:h-[100dvh] lg:max-h-[100dvh] lg:min-h-0 lg:overflow-hidden">
    @include('admin.partials.sidebar')
    <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:min-h-0 lg:overflow-hidden">
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-secondary/40 bg-white/90 px-4 backdrop-blur lg:static lg:px-8">
            <div>
                <h1 class="text-lg font-semibold text-ink">@yield('heading')</h1>
                <nav class="text-xs text-ink/60" aria-label="Breadcrumb">
                    @yield('breadcrumb')
                </nav>
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
</body>
</html>
