@props([
    'title',
    'subtitle' => null,
    'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=2000&q=80',
    'variant' => 'dark',
    'kicker' => null,
    'immersive' => false,
    'wide' => false,
])
@php
    $overlay = $variant === 'light'
        ? "linear-gradient(to top, rgba(245,247,246,.95), rgba(245,247,246,.75)), url('{$image}')"
        : "linear-gradient(to top, rgba(10,12,12,.9), rgba(10,12,12,.42)), url('{$image}')";

    $sectionClass = $immersive
        ? 'relative flex min-h-[min(52vh,520px)] sm:min-h-[min(58vh,600px)] items-end overflow-hidden pt-28 pb-16 sm:pb-20'
        : 'relative flex min-h-[42vh] items-end overflow-hidden pt-28 pb-12';

    $titleClass = $immersive && $variant === 'dark'
        ? 'font-serif text-4xl font-semibold tracking-tight text-white sm:text-5xl lg:text-[2.75rem] lg:leading-[1.12]'
        : ($variant === 'light'
            ? 'text-4xl font-bold text-primary sm:text-5xl'
            : 'text-4xl font-bold text-white sm:text-5xl');
@endphp
<section class="{{ $sectionClass }}">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: {{ $overlay }};"></div>
    @if($immersive && $variant === 'dark')
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/70 via-black/15 to-black/30" aria-hidden="true"></div>
    @endif
    <div class="site-gutter-x relative z-10 w-full">
        <div @class([
            'w-full' => $wide,
            'max-w-4xl' => ! $wide,
        ])>
            @if($variant === 'light')
                <h1 class="{{ $titleClass }}" data-aos="fade-up" data-aos-duration="850">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-4 max-w-2xl text-lg text-ink/80" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">{{ $subtitle }}</p>
                @endif
            @else
                @if($kicker)
                    <p class="mb-3 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-white/85 [text-shadow:0_1px_2px_rgba(0,0,0,0.6)]" data-aos="fade-up" data-aos-duration="650">{{ $kicker }}</p>
                @endif
                <h1 class="{{ $titleClass }}" data-aos="fade-up" data-aos-duration="850">{{ $title }}</h1>
                @if($subtitle)
                    <p @class([
                        'mt-5 text-base font-medium leading-relaxed text-white sm:text-lg [text-shadow:0_1px_2px_rgba(0,0,0,0.75),0_2px_24px_rgba(0,0,0,0.45)]',
                        'max-w-2xl' => ! $wide,
                        'max-w-4xl' => $wide,
                    ]) data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    </div>
</section>
