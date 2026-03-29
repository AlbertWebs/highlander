<div
    class="pointer-events-none fixed bottom-6 right-6 z-[60] sm:bottom-8 sm:right-8"
    x-data="{ show: false }"
    x-init="(() => {
        const onScroll = () => { show = window.scrollY > 320 };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    })()"
>
    <button
        type="button"
        class="pointer-events-auto flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-primary text-white shadow-lg transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-primary/90 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 translate-y-2"
        style="display: none;"
        @click="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })"
        aria-label="{{ __('Back to top') }}"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
        </svg>
    </button>
</div>
