{{-- Expects: $box (array title/body), $index (int 0-based) — no icons; index carries the visual --}}
@php
    $delay = min(380, 90 * (int) ($index ?? 0));
    $num = str_pad((string) (($index ?? 0) + 1), 2, '0', STR_PAD_LEFT);
@endphp
<article
    class="group relative flex h-full flex-col overflow-hidden rounded-[1.35rem] border border-secondary/40 bg-gradient-to-br from-white via-white to-primary/[0.04] p-7 shadow-[0_4px_40px_-12px_rgba(46,46,46,0.08)] ring-1 ring-black/[0.03] backdrop-blur-[2px] transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/25 hover:shadow-[0_28px_70px_-32px_rgba(46,46,46,0.14)] motion-reduce:transform-none sm:p-8 md:p-9"
    data-aos="fade-up"
    data-aos-duration="800"
    data-aos-delay="{{ $delay }}"
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/35 to-transparent opacity-80" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full bg-primary/[0.06] opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100" aria-hidden="true"></div>
    <div class="absolute left-0 top-6 bottom-6 w-[3px] rounded-full bg-gradient-to-b from-primary via-primary/75 to-accent/60" aria-hidden="true"></div>
    <div class="flex items-start justify-between gap-3 pl-1">
        <span class="font-serif text-[2.35rem] font-semibold leading-none tabular-nums text-primary/[0.14] transition-colors duration-300 group-hover:text-primary/22 sm:text-[2.75rem]" aria-hidden="true">{{ $num }}</span>
        <span class="mt-3 h-px flex-1 max-w-[5rem] bg-gradient-to-r from-accent/45 to-transparent opacity-70" aria-hidden="true"></span>
    </div>
    <h3 class="mt-5 font-serif text-xl font-semibold leading-snug tracking-tight text-ink sm:text-[1.35rem]">{{ $box['title'] }}</h3>
    @if(filled($box['body'] ?? ''))
        <p class="mt-4 flex-1 text-sm leading-relaxed text-ink/72 sm:text-[0.9375rem] sm:leading-relaxed">{{ $box['body'] }}</p>
    @endif
</article>
