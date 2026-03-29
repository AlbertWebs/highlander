@php
    $valid = fn (?string $u): bool => is_string($u) && $u !== '' && filter_var($u, FILTER_VALIDATE_URL);
    $rows = [
        ['href' => $social_facebook ?? '', 'label' => 'Facebook', 'key' => 'fb'],
        ['href' => $social_instagram ?? '', 'label' => 'Instagram', 'key' => 'ig'],
        ['href' => $social_youtube ?? '', 'label' => 'YouTube', 'key' => 'yt'],
        ['href' => $social_twitter ?? '', 'label' => 'X', 'key' => 'x'],
        ['href' => $social_tiktok ?? '', 'label' => 'TikTok', 'key' => 'tt'],
    ];
    $show = collect($rows)->contains(fn ($r) => $valid($r['href']));
@endphp

@if($show)
    <div class="pointer-events-none absolute bottom-32 right-4 z-20 sm:bottom-36 sm:right-6 md:bottom-40 md:right-10 lg:bottom-44 xl:right-14" data-aos="fade-left" data-aos-duration="800" data-aos-delay="280">
        <div class="pointer-events-auto flex flex-col items-end gap-2.5">
            @foreach($rows as $row)
                @if(! $valid($row['href']))
                    @continue
                @endif
                <a
                    href="{{ $row['href'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/35 bg-black/30 text-white shadow-lg backdrop-blur-md transition hover:border-primary/80 hover:bg-primary/25 hover:text-white focus:outline-none focus:ring-2 focus:ring-primary/60"
                    aria-label="{{ $row['label'] }}"
                >
                    @switch($row['key'])
                        @case('fb')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12a10 10 0 10-11.5 9.95v-7.05h-2V12h2V9.5c0-2 1.2-3.1 3-3.1.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.2l-.35 2.9h-1.85v7.05A10 10 0 0022 12z"/></svg>
                            @break
                        @case('ig')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3.5A4.5 4.5 0 1110.5 12 4.5 4.5 0 0112 7.5zm6.5-.75a1 1 0 11-1 1 1 1 0 011-1z"/></svg>
                            @break
                        @case('yt')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 7.2a3 3 0 00-2.1-2.1C19.5 4.5 12 4.5 12 4.5s-7.5 0-9.4.6A3 3 0 00.5 7.2 30 30 0 000 12a30 30 0 00.5 4.8 3 3 0 002.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 002.1-2.1A30 30 0 0024 12a30 30 0 00-.5-4.8zM9.75 15.02v-6l5.5 3-5.5 3z"/></svg>
                            @break
                        @case('x')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2H21l-7.5 8.59L22 22h-6.51l-5.1-6.35L5.5 22H2.8l8.02-9.2L2 2h6.65l4.6 5.78L18.244 2z"/></svg>
                            @break
                        @case('tt')
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/></svg>
                            @break
                    @endswitch
                </a>
            @endforeach
        </div>
    </div>
@endif
