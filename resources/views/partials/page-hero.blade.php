@props(['title', 'subtitle' => null, 'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=2000&q=80', 'variant' => 'dark'])
@php
    $overlay = $variant === 'light'
        ? "linear-gradient(to top, rgba(245,247,246,.95), rgba(245,247,246,.75)), url('{$image}')"
        : "linear-gradient(to top, rgba(10,12,12,.88), rgba(10,12,12,.45)), url('{$image}')";
@endphp
<section class="relative flex min-h-[42vh] items-end overflow-hidden pt-28 pb-12">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: {{ $overlay }};"></div>
    <div class="site-gutter-x relative z-10 w-full">
        <div class="max-w-4xl">
            @if($variant === 'light')
                <h1 class="text-4xl font-bold text-primary sm:text-5xl" data-aos="fade-up" data-aos-duration="850">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-4 max-w-2xl text-lg text-ink/80" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">{{ $subtitle }}</p>
                @endif
            @else
                <h1 class="text-4xl font-bold text-white sm:text-5xl" data-aos="fade-up" data-aos-duration="850">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-4 max-w-2xl text-lg text-white/85" data-aos="fade-up" data-aos-duration="800" data-aos-delay="120">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    </div>
</section>
