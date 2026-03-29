{{-- Expects: $card, $first (bool|null): true = first statement after grid, false = later statements, null = embedded (parent handles spacing) --}}
<article
    @class([
        'relative overflow-hidden rounded-[1.75rem] border border-primary/20 bg-gradient-to-br from-white via-primary/[0.05] to-tint-green/50 px-8 py-12 shadow-[0_28px_90px_-32px_rgba(46,46,46,0.18)] ring-1 ring-white/60 backdrop-blur-[1px] sm:px-12 sm:py-14 md:px-16',
        'mt-14 lg:mt-20' => ($first ?? null) === true,
        'mt-10 lg:mt-12' => ($first ?? null) === false,
        'mt-0' => ($first ?? null) === null,
    ])
    data-aos="fade-up"
    data-aos-duration="850"
    data-aos-delay="80"
>
    <div class="pointer-events-none absolute inset-x-10 top-0 h-px bg-gradient-to-r from-transparent via-primary/35 to-transparent sm:inset-x-14" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-16 top-0 h-52 w-52 rounded-full bg-primary/18 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-10 left-8 h-40 w-40 rounded-full bg-accent/25 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 rounded-[1.75rem] opacity-[0.35] [background:radial-gradient(ellipse_80%_50%_at_50%_0%,rgba(76,175,80,0.12),transparent_65%)]" aria-hidden="true"></div>
    <div class="relative w-full max-w-none text-center">
        <h3 class="font-serif text-xl font-semibold tracking-tight text-ink sm:text-2xl">{{ $card->title }}</h3>
        <div class="about-rich-text mt-6 text-base leading-relaxed text-ink/[0.88] sm:text-lg sm:leading-relaxed">{!! $card->body !!}</div>
    </div>
</article>
