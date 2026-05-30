{{-- Expects: $val (AboutCoreValue), $index (int) — no icons; typography + structure carry the design --}}
@php
    $delay = min(300, 50 * (int) ($index ?? 0));
@endphp
<article
    class="group relative flex h-full flex-col overflow-hidden rounded-[1.35rem] border border-secondary/45 bg-white/80 p-6 shadow-soft ring-1 ring-black/[0.02] backdrop-blur-[2px] transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 hover:shadow-[0_24px_60px_-28px_rgba(46,46,46,0.14)] motion-reduce:transform-none sm:p-7 md:p-8"
    data-aos="fade-up"
    data-aos-duration="800"
    data-aos-delay="{{ $delay }}"
>
    <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-primary/[0.07] opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
    <div class="absolute left-0 top-5 bottom-5 w-[3px] rounded-full bg-gradient-to-b from-primary via-primary/80 to-accent/65" aria-hidden="true"></div>
    <span class="mb-3 block font-serif text-[2rem] font-semibold leading-none tabular-nums text-primary/[0.18] transition-colors duration-300 group-hover:text-primary/28 sm:text-[2.5rem]" aria-hidden="true">{{ str_pad((string) (($index ?? 0) + 1), 2, '0', STR_PAD_LEFT) }}</span>
    <h3 class="font-serif text-lg font-semibold tracking-tight text-ink sm:text-xl">{{ $val->title }}</h3>
    @if(filled($val->description))
        <div class="about-rich-text about-rich-text--sm mt-3 flex-1 text-sm leading-relaxed text-ink/75 sm:text-[15px]">{!! $val->description !!}</div>
    @endif
</article>
