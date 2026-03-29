{{-- Parent must wrap with x-data="heroVideoLoading()" and flex centering --}}
<div
    x-show="loading"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-400"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    role="status"
    aria-live="polite"
    aria-label="{{ __('Loading video') }}"
>
    <div class="rounded-full border border-white/30 bg-black/55 px-6 py-5 shadow-[0_8px_40px_rgba(0,0,0,0.45)] backdrop-blur-md ring-1 ring-white/10">
        <img
            src="{{ asset('images/hero-video-loading.gif') }}"
            alt=""
            width="64"
            height="64"
            class="h-14 w-14 object-contain opacity-95 sm:h-16 sm:w-16"
            loading="eager"
            decoding="async"
        />
    </div>
</div>
