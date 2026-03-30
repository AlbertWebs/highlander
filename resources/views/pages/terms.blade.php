@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Terms of Use').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Terms of Use'), 'subtitle' => __('Using this website and our services.')])

<section class="site-gutter-x mx-auto w-full py-14 pb-20" data-aos="fade-up" data-aos-duration="850">
    <div class="legal-rich-text prose prose-lg max-w-none text-ink prose-headings:text-primary prose-a:text-primary prose-p:my-6 prose-p:leading-8 prose-headings:mt-10 prose-headings:mb-4">
        @if(filled($termsContent ?? ''))
            {!! $termsContent !!}
        @else
            <p class="text-lg text-ink/80">{{ __('These terms govern your use of our website. By browsing or submitting an enquiry, you agree to them. Last updated :date.', ['date' => now()->format('F j, Y')]) }}</p>

            <h2>{{ __('Website content') }}</h2>
            <p>{{ __('Text, images, itineraries, and prices on this site are for general information and may change. We aim for accuracy but do not guarantee that all content is complete or up to date at every moment.') }}</p>

            <h2>{{ __('Enquiries & bookings') }}</h2>
            <p>{{ __('Trip proposals, quotes, and confirmations are provided separately. A contract is formed only when agreed in writing (including email) according to our booking conditions supplied at that stage.') }}</p>

            <h2>{{ __('Limitation of liability') }}</h2>
            <p>{{ __('To the extent permitted by law, we are not liable for indirect loss or for events outside our reasonable control (including third-party suppliers, weather, or travel disruptions). Nothing in these terms limits liability that cannot legally be excluded.') }}</p>

            <h2>{{ __('Intellectual property') }}</h2>
            <p>{{ __('Branding, copy, and original media on this site belong to us or our licensors unless credited otherwise. You may not reuse them for commercial purposes without permission.') }}</p>

            <h2>{{ __('External links') }}</h2>
            <p>{{ __('We may link to other sites for convenience. We are not responsible for their content or privacy practices.') }}</p>

            <h2>{{ __('Changes') }}</h2>
            <p>{{ __('We may update these terms from time to time. Continued use of the site after changes constitutes acceptance of the revised terms.') }}</p>

            <h2>{{ __('Contact') }}</h2>
            <p><a href="{{ route('contact') }}" class="font-medium text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Contact us') }}</a> {{ __('if you have questions about these terms.') }}</p>
        @endif
    </div>
</section>
@endsection
