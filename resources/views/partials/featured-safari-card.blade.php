@php
    /** @var \App\Models\SafariExperience $safari */
@endphp
<article
    class="group flex min-w-0 flex-col overflow-hidden card-depth ring-1 ring-primary/10 backdrop-blur-sm"
    data-aos="fade-up"
    data-aos-duration="800"
    data-aos-delay="{{ min(400, 100 * ($cardIndex ?? $loop->index ?? 0)) }}"
>
    <div class="h-1.5 w-full bg-gradient-to-r from-primary via-accent to-primary bg-[length:200%_100%] bg-left transition duration-500 group-hover:bg-right"></div>
    <a href="{{ route('safari.show', $safari) }}" class="img-zoom-parent relative block aspect-[4/3] overflow-hidden bg-gradient-to-br from-secondary/50 to-primary/20">
        <img src="{{ $safari->cardImageUrl() }}" alt="" class="h-full w-full object-cover img-zoom-hover" loading="lazy">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/60 via-black/5 to-primary/10 opacity-90 transition duration-300 group-hover:from-black/50 group-hover:via-transparent group-hover:to-primary/20"></div>
        @if(filled($safari->duration))
            <p class="absolute bottom-3 left-3 rounded-full border border-white/30 bg-primary/95 px-3 py-1 text-xs font-bold text-white shadow-lg ring-2 ring-primary/30">{{ $safari->duration }}</p>
        @endif
    </a>
    <div class="flex flex-1 flex-col bg-gradient-to-b from-white via-white to-primary/[0.04] p-5 sm:p-6">
        <h3 class="font-serif text-lg font-semibold leading-snug tracking-tight text-ink transition group-hover:text-primary lg:text-[1.125rem]">
            <a href="{{ route('safari.show', $safari) }}" class="hover:text-primary">{{ $safari->title }}</a>
        </h3>
        @if(filled($safari->description))
            <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-ink/75">{{ \Illuminate\Support\Str::limit(strip_tags((string) $safari->description), 200) }}</p>
        @endif
        <div class="mt-5 grid grid-cols-2 gap-2 sm:gap-2.5 lg:grid-cols-1 lg:gap-2">
            <a
                href="{{ route('plan-my-safari', ['safari' => $safari->slug]) }}"
                class="btn-primary min-w-0 w-full justify-center bg-gradient-to-r from-primary via-primary to-accent px-2.5 py-2 text-[0.625rem] leading-tight tracking-[0.04em] hover:brightness-110 sm:px-3 sm:text-[0.6875rem] sm:tracking-[0.05em] lg:py-2 lg:text-xs"
            >{{ __('Plan This Safari') }}</a>
            <a
                href="{{ route('safari.show', $safari) }}"
                class="btn-secondary min-w-0 w-full justify-center px-2.5 py-2 text-[0.625rem] leading-tight tracking-[0.04em] sm:px-3 sm:text-[0.6875rem] sm:tracking-[0.05em] lg:py-2 lg:text-xs"
            >{{ __('Explore this Safari') }}</a>
        </div>
    </div>
</article>
