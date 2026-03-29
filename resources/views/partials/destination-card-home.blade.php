@php
    /** @var \App\Models\Destination $dest */
    $fluid = ! empty($fluid);
@endphp
<article @class([
    'group img-zoom-parent overflow-hidden bg-surface card-depth',
    'w-full min-w-0 max-w-full' => $fluid,
    'w-[min(85vw,320px)] shrink-0 sm:w-[300px] lg:w-[320px]' => ! $fluid,
])>
    <div class="relative aspect-[4/3] overflow-hidden bg-secondary/50">
        <img
            src="{{ $dest->cardImageUrl() }}"
            alt="{{ $dest->name }}"
            class="img-zoom-hover h-full w-full object-cover"
            loading="lazy"
            decoding="async"
        >
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/45 via-black/5 to-transparent opacity-90"></div>
    </div>
    <div class="border-t border-secondary/40 bg-surface p-5">
        <h3 class="font-serif text-lg font-semibold leading-snug text-primary">{{ $dest->name }}</h3>
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-ink/70">{{ $dest->description }}</p>
    </div>
</article>
