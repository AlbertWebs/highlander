@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Photo Credits').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Photo Credits'), 'subtitle' => __('Attribution for photography and media used on this site.')])

<section class="site-gutter-x mx-auto max-w-3xl py-14 pb-20" data-aos="fade-up" data-aos-duration="850">
    <div class="prose prose-lg max-w-none text-ink prose-headings:text-primary prose-a:text-primary prose-p:my-5 prose-p:leading-8">
        @if(filled($photoCreditsContent ?? ''))
            {!! $photoCreditsContent !!}
        @else
            <p class="text-lg text-ink/80">{{ __('We are grateful to the photographers and platforms whose work helps illustrate the spirit of African travel. Gallery and article imagery may be our own or supplied by partners; where stock imagery is used, credits are listed below.') }}</p>

            <h2>{{ __('Site chrome & layout imagery') }}</h2>
            <ul>
                <li>
                    {{ __('Footer and some hero imagery use stock photography from') }}
                    <a href="https://unsplash.com" rel="noopener noreferrer" target="_blank">Unsplash</a>
                    {{ __('under the') }}
                    <a href="https://unsplash.com/license" rel="noopener noreferrer" target="_blank">{{ __('Unsplash License') }}</a>.
                    {{ __('Each image page on Unsplash names the photographer.') }}
                </li>
            </ul>

            <h2>{{ __('Video') }}</h2>
            <p>{{ __('Homepage hero video may be embedded from Vimeo or similar platforms; playback is subject to their terms and the rights of the uploader.') }}</p>

            <h2>{{ __('Your imagery') }}</h2>
            <p>{{ __('If you believe an image has been used incorrectly or wish to request a credit update, please') }}
                <a href="{{ route('contact') }}" class="font-medium text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('contact us') }}</a>.</p>
        @endif
    </div>
</section>
@endsection
