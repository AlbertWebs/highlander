@php
    /** @var \App\Models\Destination $dest */
    $fluid = ! empty($fluid);
@endphp
<a
    href="{{ route('explore-africa.show', $dest) }}"
    @class([
        'group img-zoom-parent block overflow-hidden bg-surface card-depth outline-none ring-primary/40 transition hover:ring-2 focus-visible:ring-2',
        'w-full min-w-0 max-w-full' => $fluid,
        'w-[min(85vw,320px)] shrink-0 sm:w-[300px] lg:w-[320px]' => ! $fluid,
    ])
>
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
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-ink/70">{{ \Illuminate\Support\Str::limit(strip_tags((string) $dest->description), 160) }}</p>
    </div>
</a>
