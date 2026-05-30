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
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/65 via-black/25 to-black/20" aria-hidden="true"></div>
    <div class="site-gutter-x relative z-10 w-full">
        <nav class="mb-6 text-sm text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('Home') }}</a></li>
                <li class="text-white/40" aria-hidden="true">/</li>
                <li class="font-medium text-white">{{ __('About Us') }}</li>
            </ol>
        </nav>
        <div class="w-full pb-2">
            <h1 id="about-hero-heading" class="text-4xl font-bold tracking-tight text-white sm:text-5xl" data-aos="fade-up" data-aos-duration="850">
                {{ $setting->hero_title }}
            </h1>
            @if(filled($setting->hero_subtitle))
                <div
                    class="about-rich-text about-rich-text--hero mt-5 w-full max-w-none text-xl font-medium leading-relaxed text-white sm:mt-6 sm:text-2xl sm:leading-snug [text-shadow:0_1px_3px_rgba(0,0,0,0.75),0_4px_28px_rgba(0,0,0,0.55)]"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="100"
                >
                    {!! $setting->hero_subtitle !!}
                </div>
            @endif
        </div>
    </div>
</section>
