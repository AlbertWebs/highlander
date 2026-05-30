{{-- Expects: $box (array icon/title/body), $index (int 0-based) --}}
@php
    $delay = min(420, 80 * (int) ($index ?? 0));
    $num = str_pad((string) (($index ?? 0) + 1), 2, '0', STR_PAD_LEFT);
    $hasIcon = filled($box['icon'] ?? '');
@endphp
<article
    class="group relative flex h-full flex-col overflow-hidden rounded-[1.35rem] border border-secondary/40 bg-gradient-to-br from-white via-white to-primary/[0.05] p-6 shadow-[0_4px_40px_-12px_rgba(46,46,46,0.08)] ring-1 ring-black/[0.03] transition duration-300 ease-out hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-[0_24px_60px_-28px_rgba(46,46,46,0.16)] motion-reduce:transform-none sm:p-7 lg:p-8"
    data-aos="fade-up"
    data-aos-duration="800"
    data-aos-delay="{{ $delay }}"
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent" aria-hidden="true"></div>
    <div class="absolute left-0 top-5 bottom-5 w-[3px] rounded-full bg-gradient-to-b from-primary via-primary/70 to-accent/50" aria-hidden="true"></div>

    <div class="flex items-start gap-4 pl-2">
        @if($hasIcon)
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/[0.08] text-xl ring-1 ring-primary/15" aria-hidden="true">{{ $box['icon'] }}</span>
        @else
            <span class="font-serif text-2xl font-semibold leading-none tabular-nums text-primary/20 transition-colors duration-300 group-hover:text-primary/30" aria-hidden="true">{{ $num }}</span>
        @endif
        <div class="min-w-0 flex-1">
            <h3 class="font-serif text-lg font-semibold leading-snug tracking-tight text-ink sm:text-xl">{{ $box['title'] }}</h3>
            @if(filled($box['body'] ?? ''))
                <p class="mt-2 text-sm leading-relaxed text-ink/70">{{ $box['body'] }}</p>
            @endif
        </div>
    </div>
</article>
