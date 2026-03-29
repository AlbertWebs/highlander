@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Privacy Policy').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Privacy Policy'), 'subtitle' => __('How we handle your information.')])

<section class="site-gutter-x mx-auto max-w-3xl py-14 pb-20" data-aos="fade-up" data-aos-duration="850">
    <div class="prose prose-lg max-w-none text-ink prose-headings:text-primary prose-a:text-primary">
        <p class="text-lg text-ink/80">{{ __('This policy describes how we collect and use personal data when you use our website or contact us.') }} {{ __('Last updated :date.', ['date' => now()->format('F j, Y')]) }}</p>

        <h2>{{ __('Information we collect') }}</h2>
        <p>{{ __('We may collect your name, email address, phone number, and message content when you submit our contact or enquiry forms. Technical data such as browser type, approximate region, and pages visited may be processed by our hosting and analytics tools in aggregated form.') }}</p>

        <h2>{{ __('How we use it') }}</h2>
        <p>{{ __('We use this information to respond to enquiries, plan itineraries, improve our website, and comply with legal obligations. We do not sell your personal data.') }}</p>

        <h2>{{ __('Cookies') }}</h2>
        <p>{{ __('Our site may use essential cookies for security and session functionality. If we add analytics or marketing cookies, we will update this policy and, where required, ask for your consent.') }}</p>

        <h2>{{ __('Retention & security') }}</h2>
        <p>{{ __('We keep enquiry records only as long as needed for customer service, bookings, or legal requirements. We apply reasonable technical and organisational measures to protect your data.') }}</p>

        <h2>{{ __('Your rights') }}</h2>
        <p>{{ __('Depending on your jurisdiction, you may have rights to access, correct, or delete your personal data, or to object to certain processing.') }}
            <a href="{{ route('contact') }}" class="font-medium text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Contact us') }}</a>
            {{ __('for assistance.') }}</p>

        <h2>{{ __('Contact') }}</h2>
        <p>{{ __('For privacy-related questions, please reach out via our contact page or the email shown in the site footer.') }}</p>
    </div>
</section>
@endsection
