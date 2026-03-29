@props(['title' => null, 'subtitle' => null])
@php
    $heading = $title ?? __('Gallery');
    $bg = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=2400&q=80';
    $overlay = "linear-gradient(to top, rgba(10,12,12,.82), rgba(10,12,12,.38)), url('{$bg}')";
@endphp
<section class="relative flex min-h-[38vh] max-h-[48vh] items-end overflow-hidden pt-28 pb-14 sm:min-h-[42vh]" aria-labelledby="gallery-hero-heading">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: {{ $overlay }};"></div>
    <div class="site-gutter-x relative z-10 w-full">
        <nav class="mb-5 text-sm text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('Home') }}</a></li>
                <li class="text-white/40" aria-hidden="true">/</li>
                <li class="font-medium text-white">{{ __('Gallery') }}</li>
            </ol>
        </nav>
        <h1 id="gallery-hero-heading" class="inline-block font-serif text-4xl font-semibold tracking-tight text-white sm:text-5xl" data-aos="fade-up" data-aos-duration="850">
            <span class="border-b-2 border-white/90 pb-1">{{ $heading }}</span>
        </h1>
        @if(filled($subtitle))
            <p class="mt-5 max-w-2xl text-base text-white/85 sm:text-lg" data-aos="fade-up" data-aos-duration="800" data-aos-delay="80">{{ $subtitle }}</p>
        @endif
    </div>
</section>
