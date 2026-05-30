@php
    $adminLogoPath = \App\Models\SiteSetting::getValue('site_logo', '');
    $adminLogoDarkPath = \App\Models\SiteSetting::getValue('site_logo_dark', '');
    $adminLogoUrl = \App\Models\SiteSetting::publicUrl($adminLogoPath) ?? \App\Models\SiteSetting::publicUrl($adminLogoDarkPath);

    $icons = [
        'squares' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
        'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5',
        'map' => 'M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437a.75.75 0 00.503-.698v-11.956a.75.75 0 00-.503-.698L15 6.502m-6 0L4.127 3.939a.75.75 0 00-.503.698v11.956c0 .284.162.545.416.668l4.875 2.437M9 6.75l6-3.375',
        'globe' => 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418',
        'mountain' => 'M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941',
        'camera' => 'M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316zM15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z',
        'photo' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M4.5 19.5h15A2.25 2.25 0 0021.75 18V6a2.25 2.25 0 00-2.25-2.25H3A2.25 2.25 0 00.75 6v12a2.25 2.25 0 002.25 2.25zm10.5-11.25h.008v.008H15V8.25z',
        'doc' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'chat' => 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V4.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155',
        'users' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z',
        'sparkles' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z',
        'cog' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'folder' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z',
        'inbox' => 'M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.861M16.5 18.75h-9m9-3H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m0-12.75h17.25m-17.25 0A1.125 1.125 0 005.25 3v10.5M3.375 18.75h7.5c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 00-.054-.647m-4.119 1.076A2.916 2.916 0 0112 15.75c-1.028 0-1.966-.512-2.52-1.357m0 0a2.916 2.916 0 01-.882-2.082M15 10.5a3 3 0 11-6 0 3 3 0 016 0z',
        'magnify' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
        'envelope' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
    ];

    $navGroups = [
        ['label' => null, 'items' => [
            ['r' => 'admin.dashboard', 'l' => __('Dashboard'), 'i' => 'squares'],
        ]],
        ['label' => __('Business'), 'items' => [
            ['r' => 'admin.bookings.index', 'l' => __('Bookings'), 'i' => 'calendar'],
            ['r' => 'admin.safari-requests.index', 'l' => __('Safari Requests'), 'i' => 'map'],
        ]],
        ['label' => __('Content'), 'items' => [
            ['r' => 'admin.tours.index', 'l' => __('Tours'), 'i' => 'map'],
            ['r' => 'admin.destinations.index', 'l' => __('Destinations'), 'i' => 'globe'],
            ['r' => 'admin.mountains.index', 'l' => __('Mountains'), 'i' => 'mountain'],
            ['r' => 'admin.safari.index', 'l' => __('Safari'), 'i' => 'camera'],
            ['r' => 'admin.gallery.index', 'l' => __('Gallery'), 'i' => 'photo'],
            ['r' => 'admin.articles.index', 'l' => __('Articles'), 'i' => 'doc'],
            ['r' => 'admin.testimonials.index', 'l' => __('Testimonials'), 'i' => 'chat'],
            ['r' => 'admin.homepage.edit', 'l' => __('Homepage'), 'i' => 'sparkles'],
            ['r' => 'admin.about-page.edit', 'l' => __('About page'), 'i' => 'doc'],
        ]],
        ['label' => __('People & comms'), 'items' => [
            ['r' => 'admin.users.index', 'l' => __('Users'), 'i' => 'users'],
            ['r' => 'admin.contact-messages.index', 'l' => __('Messages'), 'i' => 'inbox'],
            ['r' => 'admin.newsletter-subscribers.index', 'l' => __('Newsletter'), 'i' => 'envelope'],
        ]],
        ['label' => __('Site'), 'items' => [
            ['r' => 'admin.settings.edit', 'l' => __('Settings'), 'i' => 'cog'],
            ['r' => 'admin.media.index', 'l' => __('Media Manager'), 'i' => 'folder'],
            ['r' => 'admin.seo.index', 'l' => __('SEO'), 'i' => 'magnify'],
        ]],
    ];

    $routeActive = function (string $rn): bool {
        if (str_ends_with($rn, '.index')) {
            return request()->routeIs(\Illuminate\Support\Str::beforeLast($rn, '.').'.*');
        }

        return request()->routeIs($rn) || request()->routeIs($rn.'.*');
    };
@endphp
<aside
    :class="sidebarOpen ? 'w-[17rem]' : 'w-[4.25rem]'"
    class="fixed inset-y-0 left-0 z-40 flex min-h-screen flex-col border-r border-white/[0.06] bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-200 shadow-[4px_0_24px_rgba(0,0,0,0.12)] transition-[width] duration-300 ease-out lg:static lg:h-full lg:max-h-[100dvh] lg:min-h-0 lg:shrink-0 lg:self-stretch"
>
    {{-- subtle brand edge --}}
    <div class="pointer-events-none absolute inset-y-0 left-0 w-px bg-gradient-to-b from-primary/50 via-accent/40 to-primary/30" aria-hidden="true"></div>

    <div class="relative flex h-[4.25rem] shrink-0 items-center gap-2 border-b border-white/[0.06] px-3">
        <a
            href="{{ route('admin.dashboard') }}"
            class="flex min-w-0 flex-1 items-center gap-2.5 rounded-xl px-2 py-1.5 text-white transition hover:bg-white/[0.04]"
            :class="!sidebarOpen && 'justify-center'"
            x-bind:title="sidebarOpen ? '' : {{ json_encode(__('Dashboard')) }}"
        >
            @if($adminLogoUrl)
                <img src="{{ $adminLogoUrl }}" alt="" class="h-8 w-auto max-w-[7.5rem] shrink-0 object-contain object-left" width="120" height="32">
            @else
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary/90 to-primary/60 text-sm font-bold text-white shadow-lg shadow-primary/20">H</span>
            @endif
            <span class="truncate text-sm font-semibold tracking-tight" x-show="sidebarOpen" x-transition.opacity.duration.200ms>{{ __('Admin') }}</span>
        </a>
        <button
            type="button"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-slate-300 transition hover:border-primary/35 hover:bg-primary/10 hover:text-white"
            @click="sidebarOpen = !sidebarOpen"
            :aria-expanded="sidebarOpen"
            aria-label="{{ __('Toggle sidebar') }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
            </svg>
        </button>
    </div>

    <nav class="admin-sidebar-nav flex min-h-0 flex-1 flex-col gap-6 overflow-y-auto overflow-x-hidden px-2.5 py-5" aria-label="{{ __('Admin navigation') }}">
        @foreach($navGroups as $group)
            <div class="space-y-0.5">
                @if($group['label'])
                    <p
                        class="mb-2.5 px-3 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500"
                        x-show="sidebarOpen"
                        x-transition.opacity.duration.200ms
                    >{{ $group['label'] }}</p>
                @endif
                @foreach($group['items'] as $link)
                    @php
                        $active = $routeActive($link['r']);
                        $d = $icons[$link['i']] ?? $icons['squares'];
                    @endphp
                    <a
                        href="{{ route($link['r']) }}"
                        title="{{ $link['l'] }}"
                        @class([
                            'group relative flex items-center gap-3 rounded-xl py-2.5 pl-2.5 pr-3 text-[0.8125rem] font-medium transition duration-200',
                            'bg-gradient-to-r from-primary/25 via-primary/10 to-transparent text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.06)]' => $active,
                            'text-slate-400 hover:bg-white/[0.05] hover:text-slate-100' => ! $active,
                        ])
                        :class="!sidebarOpen && 'justify-center px-0'"
                    >
                        @if($active)
                            <span class="absolute left-0 top-1/2 h-8 w-1 -translate-y-1/2 rounded-r-full bg-gradient-to-b from-primary to-accent shadow-[0_0_12px_rgba(76,175,80,0.5)]" aria-hidden="true"></span>
                        @endif
                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border transition duration-200 {{ $active ? 'border-primary/30 bg-primary/15 text-primary' : 'border-white/[0.06] bg-white/[0.03] text-slate-400 group-hover:border-white/10 group-hover:bg-white/[0.06] group-hover:text-slate-200' }}">
                            <svg class="h-[1.125rem] w-[1.125rem]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}" />
                            </svg>
                        </span>
                        <span class="relative min-w-0 flex-1 truncate leading-snug" x-show="sidebarOpen" x-transition.opacity.duration.200ms>{{ $link['l'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    <div class="relative shrink-0 border-t border-white/[0.06] bg-black/20 p-2.5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="group flex w-full items-center gap-3 rounded-xl border border-white/[0.06] bg-white/[0.03] py-2.5 pl-2.5 pr-3 text-left text-[0.8125rem] font-medium text-slate-400 transition hover:border-red-500/25 hover:bg-red-500/10 hover:text-red-200"
                :class="!sidebarOpen && 'justify-center border-0 bg-transparent px-0 hover:bg-red-500/10'"
                title="{{ __('Logout') }}"
            >
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-white/[0.06] bg-white/[0.03] text-slate-400 transition group-hover:border-red-500/30 group-hover:bg-red-500/5">
                    <svg class="h-[1.125rem] w-[1.125rem]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </span>
                <span class="truncate" x-show="sidebarOpen" x-transition.opacity.duration.200ms>{{ __('Logout') }}</span>
            </button>
        </form>
        <p class="mt-2 px-1 text-center text-[0.65rem] text-slate-600" x-show="sidebarOpen" x-transition.opacity.duration.200ms>{{ config('app.name') }}</p>
    </div>
</aside>
