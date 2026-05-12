{{-- Expects: $card, $first (bool|null) like about-vision-mission-statement; optional $imageUrl --}}
@php
    $purposeImageUrl = $imageUrl ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1400&q=80';
@endphp
<article
    @class([
        'relative overflow-hidden rounded-[1.75rem] border border-primary/20 bg-gradient-to-br from-white via-primary/[0.05] to-tint-green/50 shadow-[0_28px_90px_-32px_rgba(46,46,46,0.18)] ring-1 ring-white/60 backdrop-blur-[1px]',
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
    <div class="pointer-events-none absolute inset-0 rounded-[1.75rem] opacity-[0.35] [background:radial-gradient(ellipse_80%_50%_at_0%_30%,rgba(76,175,80,0.1),transparent_60%)]" aria-hidden="true"></div>

    <div class="relative grid items-center gap-10 px-8 py-12 text-left sm:px-10 sm:py-14 lg:grid-cols-2 lg:gap-12 lg:px-12 lg:py-14 xl:gap-16">
        <div class="min-w-0">
            <h3 class="font-serif text-xl font-semibold tracking-tight text-ink sm:text-2xl lg:text-3xl">{{ $card->title }}</h3>
            <div class="about-rich-text mt-5 text-base leading-relaxed text-ink/[0.88] sm:mt-6 sm:text-lg sm:leading-relaxed [&_h3]:text-left">{!! $card->body !!}</div>
        </div>
        <div class="img-zoom-parent overflow-hidden rounded-card shadow-depth lg:justify-self-end" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">
            <img
                src="{{ $purposeImageUrl }}"
                alt=""
                class="img-zoom-hover aspect-[4/3] w-full max-w-xl object-cover lg:ml-auto lg:max-w-none"
                loading="lazy"
                width="1400"
                height="1050"
            >
        </div>
    </div>
</article>
