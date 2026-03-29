@props(['setting'])
@php
    $bg = \App\Models\SiteSetting::resolvePublicAssetUrl($setting->hero_image)
        ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=2000&q=80';
    $overlay = "linear-gradient(to top, rgba(10,12,12,.88), rgba(10,12,12,.42)), url('{$bg}')";
@endphp
<section
    class="relative flex min-h-[40vh] max-h-[50vh] items-end overflow-hidden pt-28 pb-12 sm:min-h-[42vh] lg:min-h-[45vh]"
    aria-labelledby="about-hero-heading"
>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: {{ $overlay }};"></div>
    <div class="site-gutter-x relative z-10 w-full">
        <nav class="mb-6 text-sm text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('Home') }}</a></li>
                <li class="text-white/40" aria-hidden="true">/</li>
                <li class="font-medium text-white">{{ __('About Us') }}</li>
            </ol>
        </nav>
        <div class="max-w-4xl pb-2">
            <h1 id="about-hero-heading" class="text-4xl font-bold tracking-tight text-white sm:text-5xl" data-aos="fade-up" data-aos-duration="850">
                {{ $setting->hero_title }}
            </h1>
            @if(filled($setting->hero_subtitle))
                <p class="mt-4 max-w-2xl text-lg leading-relaxed text-white/88" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    {{ $setting->hero_subtitle }}
                </p>
            @endif
        </div>
    </div>
</section>
