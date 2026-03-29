{{-- Trust & credibility row (labels + generic icons; hover scale for polish). --}}
<div
    class="mb-12 flex flex-wrap items-center justify-center gap-6 border-b border-white/10 pb-10 sm:gap-8 lg:gap-10"
    aria-label="{{ __('Trust and partners') }}"
>
    <div
        class="flex min-h-[3rem] min-w-[8.5rem] flex-col items-center justify-center rounded-badge border border-white/15 bg-white/[0.06] px-4 py-2.5 text-center transition duration-300 ease-out hover:scale-105 hover:border-white/25"
        title="{{ __('TripAdvisor') }}"
    >
        <svg class="mx-auto h-6 w-6 text-[#00af87]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
        <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-wider text-white/90">TripAdvisor</span>
    </div>
    <div
        class="flex min-h-[3rem] min-w-[8.5rem] flex-col items-center justify-center rounded-badge border border-white/15 bg-white/[0.06] px-4 py-2.5 text-center transition duration-300 ease-out hover:scale-105 hover:border-white/25"
        title="{{ __('SafariBookings') }}"
    >
        <svg class="mx-auto h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-wider text-white/90">SafariBookings</span>
    </div>
    <div
        class="flex min-h-[3rem] min-w-[8.5rem] flex-col items-center justify-center rounded-badge border border-white/15 bg-white/[0.06] px-4 py-2.5 text-center transition duration-300 ease-out hover:scale-105 hover:border-white/25"
        title="{{ __('Google Reviews') }}"
    >
        <svg class="mx-auto h-6 w-6 text-white" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-wider text-white/90">Google</span>
    </div>
    <div
        class="flex min-h-[3rem] min-w-[8.5rem] flex-col items-center justify-center rounded-badge border border-white/15 bg-white/[0.06] px-4 py-2.5 text-center transition duration-300 ease-out hover:scale-105 hover:border-white/25"
        title="{{ __('Tourism board') }}"
    >
        <svg class="mx-auto h-6 w-6 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
        <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-wider text-white/90">{{ __('Tourism') }}</span>
    </div>
    <div
        class="flex min-h-[3rem] items-center justify-center gap-2 rounded-badge border border-white/15 bg-white/[0.06] px-4 py-2.5 transition duration-300 ease-out hover:scale-105 hover:border-white/25"
        title="{{ __('Secure payment') }}"
    >
        <svg class="h-5 w-5 shrink-0 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
        <div class="text-left">
            <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-white/90">{{ __('Secure pay') }}</p>
            <p class="text-[0.6rem] text-white/55">Visa · MC · Amex</p>
        </div>
    </div>
</div>
