{{-- Expects: $item (AboutSustainabilityItem), $index (int) — no icons --}}
@php
    $delay = min(280, 55 * (int) ($index ?? 0));
@endphp
<article
    class="group relative flex h-full flex-col overflow-hidden rounded-[1.35rem] border border-secondary/40 bg-gradient-to-br from-white via-white to-primary/[0.045] p-6 shadow-soft ring-1 ring-black/[0.02] backdrop-blur-[2px] transition duration-300 ease-out hover:-translate-y-1 hover:border-accent/35 hover:shadow-[0_24px_60px_-28px_rgba(46,46,46,0.13)] motion-reduce:transform-none sm:p-7 md:p-8"
    data-aos="fade-up"
    data-aos-duration="800"
    data-aos-delay="{{ $delay }}"
>
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-tl from-primary/[0.07] via-transparent to-transparent opacity-90 transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-accent/[0.07] opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
    <div class="absolute left-0 top-5 bottom-5 w-[3px] rounded-full bg-gradient-to-b from-accent/90 via-primary/75 to-primary/45" aria-hidden="true"></div>
    <div class="relative flex items-start justify-between gap-3">
        <span class="font-serif text-[2rem] font-semibold leading-none tabular-nums text-primary/[0.16] transition-colors duration-300 group-hover:text-primary/26 sm:text-[2.5rem]" aria-hidden="true">{{ str_pad((string) (($index ?? 0) + 1), 2, '0', STR_PAD_LEFT) }}</span>
        <span class="mt-2 h-px flex-1 max-w-[4.5rem] bg-gradient-to-r from-accent/50 to-transparent opacity-60" aria-hidden="true"></span>
    </div>
    <h3 class="relative mt-4 font-serif text-lg font-semibold tracking-tight text-ink sm:text-xl">{{ $item->title }}</h3>
    @if(filled($item->description))
        <div class="about-rich-text about-rich-text--sm relative mt-3 flex-1 text-sm leading-relaxed text-ink/75 sm:text-[15px]">{!! $item->description !!}</div>
    @endif
</article>
