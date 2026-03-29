@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Plan Your Dream Safari').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@php
    $input = 'form-input-interactive mt-1 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-ink shadow-sm transition duration-200 ease-out focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25';
    $label = 'block text-sm font-medium text-ink';
    $section = 'rounded-2xl border border-secondary/40 bg-white p-6 shadow-card sm:p-8';
    $checkboxGrid = 'grid gap-3 sm:grid-cols-2 lg:grid-cols-3';
    $pf = $prefill ?? [];
    $destPref = old('destinations', $pf['destinations'] ?? []);
    $expPref = old('experience_types', $pf['experience_types'] ?? []);
    $actPref = old('activities', $pf['activities'] ?? []);
    $wizardStep = $errors->any() ? 1 : (int) old('wizard_step', 1);
    $wizardStep = max(1, min(6, $wizardStep));
@endphp

{{-- Hero --}}
<section class="relative flex min-h-[38vh] flex-col justify-end overflow-hidden pt-28 pb-10 sm:min-h-[42vh] sm:pb-14">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: linear-gradient(to top, rgba(10,12,12,.9), rgba(10,12,12,.45)), url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80');"></div>
    <div class="site-gutter-x relative z-10 w-full" data-aos="fade-up" data-aos-duration="900">
        <nav class="mb-6 text-xs font-medium text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-white">{{ __('Home') }}</a></li>
                <li aria-hidden="true" class="text-white/40">/</li>
                <li class="text-white">{{ __('Plan My Safari') }}</li>
            </ol>
        </nav>
        <h1 class="max-w-3xl font-serif text-4xl font-medium leading-tight tracking-tight text-white sm:text-5xl">{{ __('Plan Your Dream Safari') }}</h1>
        <p class="mt-4 max-w-2xl text-lg text-white/85">{{ __('Tell us about your travel plans and we will create the perfect safari experience for you.') }}</p>
    </div>
</section>

<div class="bg-surface pb-20 pt-10 sm:pt-14">
    <div class="site-gutter-x mx-auto max-w-5xl">
        @if(session('safari_success'))
            <div class="mb-8 rounded-2xl border border-primary/35 bg-primary/10 px-6 py-8 text-ink shadow-card sm:px-8" role="status" aria-live="polite">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-6">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-primary text-2xl text-white shadow-soft" aria-hidden="true">✓</div>
                    <div class="min-w-0 flex-1 space-y-3">
                        <h2 class="font-serif text-2xl font-semibold text-primary">{{ __('We received your information') }}</h2>
                        <p class="text-base leading-relaxed text-ink/90">{{ __('Thank you for completing the safari planner. Your details are saved and our team has been notified. We will get in touch using the contact information you provided.') }}</p>
                        @if(session('safari_request_id'))
                            <p class="rounded-xl border border-secondary/40 bg-white/80 px-4 py-3 text-sm text-ink/80">
                                <span class="font-medium text-ink">{{ __('Reference') }}:</span>
                                #{{ session('safari_request_id') }}
                            </p>
                        @endif
                        <a href="{{ route('plan-my-safari', request()->only('tour')) }}" class="inline-flex items-center justify-center rounded-xl border border-primary/40 bg-white px-5 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">{{ __('Plan another safari') }}</a>
                    </div>
                </div>
            </div>
        @endif

        @if(! empty($prefillTour))
            <div class="mb-8 rounded-2xl border border-primary/25 bg-primary/5 px-5 py-4 text-ink shadow-sm" role="status">
                <p class="text-sm leading-relaxed text-ink/90">
                    {{ __('We have pre-filled this form from your selected experience:') }}
                    <span class="font-semibold text-primary">{{ $prefillTour->title }}</span>.
                    {{ __('Adjust any details before submitting.') }}
                </p>
            </div>
        @endif

        @if($errors->any())
            <div
                class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-900"
                x-data
                x-init="$nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start' }))"
                role="alert"
            >
                <p class="font-semibold">{{ __('Please correct the following:') }}</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @unless(session('safari_success'))
        <form
            method="post"
            action="{{ route('plan-my-safari.store') }}"
            class="relative rounded-2xl border border-secondary/50 bg-white p-8 shadow-card sm:p-10"
            data-aos="fade-up"
            data-aos-duration="850"
            data-aos-delay="80"
            data-adults-word="{{ __('Adults') }}"
            data-children-word="{{ __('Children') }}"
            data-yes-word="{{ __('Yes') }}"
            data-no-word="{{ __('No') }}"
            data-days-word="{{ __('days') }}"
            x-data="planSafariWizard({{ json_encode(old('arrival_date', $pf['arrival_date'] ?? '')) }}, {{ json_encode(old('departure_date', $pf['departure_date'] ?? '')) }}, {{ (int) old('children', $pf['children'] ?? 0) }}, {{ $wizardStep }})"
            @submit="handleSubmit($event)"
        >
            @csrf
            <input type="hidden" name="wizard_step" :value="step">

            <div class="pointer-events-none absolute -left-[9999px] h-px w-px overflow-hidden opacity-0" aria-hidden="true">
                <label for="hp_website">{{ __('Website') }}</label>
                <input type="text" name="hp_website" id="hp_website" value="" tabindex="-1" autocomplete="off">
            </div>

            <div class="mb-8" aria-hidden="false">
                <div class="flex flex-wrap items-center justify-between gap-2 text-sm font-medium text-ink/80">
                    <span id="wizard-progress-label">{{ __('Plan your safari') }}</span>
                    <span class="tabular-nums text-primary" x-text="`${step} / ${totalSteps}`" aria-live="polite"></span>
                </div>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-secondary/40">
                    <div class="h-full rounded-full bg-primary transition-all duration-300 motion-reduce:transition-none" :style="`width: ${(step / totalSteps) * 100}%`"></div>
                </div>
            </div>

            {{-- Step 1 --}}
            <div class="{{ $section }} mb-8 border-0 p-0 shadow-none" data-wizard-step="1" x-show="step === 1" x-cloak>
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Personal information') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="{{ $label }}" for="full_name">{{ __('Full name') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="text" name="full_name" id="full_name" x-ref="fullName" value="{{ old('full_name') }}" required autocomplete="name">
                    </div>
                    <div>
                        <label class="{{ $label }}" for="email">{{ __('Email address') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="email" name="email" id="email" x-ref="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    <div>
                        <label class="{{ $label }}" for="phone">{{ __('Phone number') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="tel" name="phone" id="phone" x-ref="phone" value="{{ old('phone') }}" required autocomplete="tel">
                    </div>
                    <div>
                        <label class="{{ $label }}" for="country">{{ __('Country of residence') }}</label>
                        <input class="{{ $input }}" type="text" name="country" id="country" value="{{ old('country') }}" autocomplete="country-name">
                    </div>
                    <div>
                        <label class="{{ $label }}" for="contact_method">{{ __('Preferred contact method') }}</label>
                        <select class="{{ $input }}" name="contact_method" id="contact_method">
                            <option value="">{{ __('Select…') }}</option>
                            <option value="phone" @selected(old('contact_method') === 'phone')>{{ __('Phone') }}</option>
                            <option value="email" @selected(old('contact_method') === 'email')>{{ __('Email') }}</option>
                            <option value="whatsapp" @selected(old('contact_method') === 'whatsapp')>{{ __('WhatsApp') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="{{ $section }} mb-8 border-0 p-0 shadow-none" data-wizard-step="2" x-show="step === 2" x-cloak>
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Travel dates') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="arrival_date">{{ __('Arrival date') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="date" name="arrival_date" id="arrival_date" x-model="arrival" x-ref="arrivalDate" value="{{ old('arrival_date', $pf['arrival_date'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="departure_date">{{ __('Departure date') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="date" name="departure_date" id="departure_date" x-model="departure" x-ref="departureDate" value="{{ old('departure_date', $pf['departure_date'] ?? '') }}" required>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 sm:col-span-2">
                        <label class="inline-flex cursor-pointer items-center gap-3">
                            <input class="rounded border-secondary text-primary focus:ring-primary" type="checkbox" name="flexible_dates" value="1" @checked(old('flexible_dates'))>
                            <span class="text-sm text-ink">{{ __('Flexible dates') }}</span>
                        </label>
                    </div>
                    <div class="sm:col-span-2" x-show="tripDays !== null" x-cloak>
                        <p class="text-sm font-medium text-primary">
                            {{ __('Trip duration') }}:
                            <span x-text="tripDays"></span>
                            {{ __('days') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="{{ $section }} mb-8 border-0 p-0 shadow-none" data-wizard-step="3" x-show="step === 3" x-cloak>
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Number of travelers') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="adults">{{ __('Number of adults') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="number" name="adults" id="adults" x-ref="adults" min="1" max="50" value="{{ old('adults', $pf['adults'] ?? 2) }}" required>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="children">{{ __('Number of children') }}</label>
                        <input class="{{ $input }}" type="number" name="children" id="children" x-ref="children" min="0" max="30" x-model.number="children" value="{{ old('children', $pf['children'] ?? 0) }}">
                    </div>
                    <div class="sm:col-span-2" x-show="children > 0" x-cloak>
                        <label class="{{ $label }}" for="children_ages">{{ __('Ages of children') }}</label>
                        <input class="{{ $input }}" type="text" name="children_ages" id="children_ages" value="{{ old('children_ages') }}" placeholder="{{ __('e.g. 5, 8, 12') }}">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="{{ $label }}" for="group_type">{{ __('Group type') }}</label>
                        <select class="{{ $input }}" name="group_type" id="group_type">
                            <option value="">{{ __('Select…') }}</option>
                            <option value="solo" @selected(old('group_type') === 'solo')>{{ __('Solo') }}</option>
                            <option value="couple" @selected(old('group_type') === 'couple')>{{ __('Couple') }}</option>
                            <option value="family" @selected(old('group_type') === 'family')>{{ __('Family') }}</option>
                            <option value="friends" @selected(old('group_type') === 'friends')>{{ __('Friends') }}</option>
                            <option value="corporate" @selected(old('group_type') === 'corporate')>{{ __('Corporate') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Step 4: destinations, experience, stay, transport, budget --}}
            <div class="{{ $section }} mb-8 border-0 p-0 shadow-none" data-wizard-step="4" x-show="step === 4" x-cloak>
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Destination preferences') }}</h2>
                <p class="mt-2 text-sm text-ink/70">{{ __('Select all that apply') }}</p>
                <div class="{{ $checkboxGrid }} mt-4">
                    @foreach(['Maasai Mara','Amboseli','Tsavo','Lake Nakuru','Samburu','Serengeti','Ngorongoro','Zanzibar','Mount Kenya'] as $dest)
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-secondary/50 bg-surface/50 px-3 py-2.5 text-sm transition hover:border-primary/40">
                            <input type="checkbox" name="destinations[]" value="{{ $dest }}" class="rounded border-secondary text-primary focus:ring-primary" @checked(is_array($destPref) && in_array($dest, $destPref, true))>
                            <span>{{ $dest }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-4">
                    <label class="{{ $label }}" for="other_destination">{{ __('Other destination') }}</label>
                    <input class="{{ $input }}" type="text" name="other_destination" id="other_destination" value="{{ old('other_destination') }}" placeholder="{{ __('Specify other place') }}">
                </div>

            <div class="mt-8 border-t border-secondary/20 pt-8">
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Type of safari experience') }}</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach([__('Wildlife Safari'),__('Luxury Safari'),__('Budget Safari'),__('Family Safari'),__('Honeymoon Safari'),__('Photography Safari'),__('Adventure Safari'),__('Cultural Experience'),__('Mountain Climbing'),__('Beach Holiday'),__('Bird Watching')] as $exp)
                        <label class="group flex cursor-pointer items-start gap-3 rounded-xl border border-secondary/50 bg-surface/50 p-4 text-sm transition hover:border-primary/50 hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="checkbox" name="experience_types[]" value="{{ $exp }}" class="mt-0.5 rounded border-secondary text-primary focus:ring-primary" @checked(is_array($expPref) && in_array($exp, $expPref, true))>
                            <span>{{ $exp }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 border-t border-secondary/20 pt-8">
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Accommodation preferences') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="accommodation_type">{{ __('Accommodation') }}</label>
                        <select class="{{ $input }}" name="accommodation_type" id="accommodation_type">
                            <option value="">{{ __('Select…') }}</option>
                            <option value="luxury_lodge" @selected(old('accommodation_type', $pf['accommodation_type'] ?? '') === 'luxury_lodge')>{{ __('Luxury Lodge') }}</option>
                            <option value="mid_lodge" @selected(old('accommodation_type', $pf['accommodation_type'] ?? '') === 'mid_lodge')>{{ __('Mid-range Lodge') }}</option>
                            <option value="budget_camp" @selected(old('accommodation_type', $pf['accommodation_type'] ?? '') === 'budget_camp')>{{ __('Budget Camp') }}</option>
                            <option value="tented_camp" @selected(old('accommodation_type', $pf['accommodation_type'] ?? '') === 'tented_camp')>{{ __('Tented Camp') }}</option>
                            <option value="boutique_hotel" @selected(old('accommodation_type', $pf['accommodation_type'] ?? '') === 'boutique_hotel')>{{ __('Boutique Hotel') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="room_type">{{ __('Room type') }}</label>
                        <select class="{{ $input }}" name="room_type" id="room_type">
                            <option value="">{{ __('Select…') }}</option>
                            <option value="single" @selected(old('room_type') === 'single')>{{ __('Single') }}</option>
                            <option value="double" @selected(old('room_type') === 'double')>{{ __('Double') }}</option>
                            <option value="twin" @selected(old('room_type') === 'twin')>{{ __('Twin') }}</option>
                            <option value="family" @selected(old('room_type') === 'family')>{{ __('Family room') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-secondary/20 pt-8">
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Transportation') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="transport_type">{{ __('Transport type') }}</label>
                        <select class="{{ $input }}" name="transport_type" id="transport_type">
                            <option value="">{{ __('Select…') }}</option>
                            <option value="4x4_safari" @selected(old('transport_type', $pf['transport_type'] ?? '') === '4x4_safari')>{{ __('4×4 Safari Vehicle') }}</option>
                            <option value="land_cruiser" @selected(old('transport_type', $pf['transport_type'] ?? '') === 'land_cruiser')>{{ __('Land Cruiser') }}</option>
                            <option value="safari_van" @selected(old('transport_type', $pf['transport_type'] ?? '') === 'safari_van')>{{ __('Safari Van') }}</option>
                            <option value="flight_transfer" @selected(old('transport_type', $pf['transport_type'] ?? '') === 'flight_transfer')>{{ __('Flight transfer') }}</option>
                            <option value="self_drive" @selected(old('transport_type', $pf['transport_type'] ?? '') === 'self_drive')>{{ __('Self drive') }}</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex cursor-pointer items-center gap-3 pb-3">
                            <input class="rounded border-secondary text-primary focus:ring-primary" type="checkbox" name="airport_pickup" value="1" @checked(old('airport_pickup'))>
                            <span class="text-sm font-medium text-ink">{{ __('Airport pickup') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-secondary/20 pt-8">
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Budget range') }}</h2>
                <div class="mt-4 space-y-3">
                    @foreach([
                        'under_1000' => __('Under $1,000'),
                        '1000_2500' => __('$1,000 – $2,500'),
                        '2500_5000' => __('$2,500 – $5,000'),
                        '5000_plus' => __('$5,000+'),
                        'not_sure' => __('Not sure'),
                    ] as $val => $lab)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-secondary/50 px-4 py-3 text-sm transition hover:bg-surface/80 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                            <input type="radio" name="budget_range" value="{{ $val }}" class="border-secondary text-primary focus:ring-primary" @checked(old('budget_range', $pf['budget_range'] ?? '') === $val)>
                            <span>{{ $lab }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            </div>

            {{-- Step 5 --}}
            <div class="{{ $section }} mb-8 border-0 p-0 shadow-none" data-wizard-step="5" x-show="step === 5" x-cloak>
                <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Activities & interests') }}</h2>
                <div class="{{ $checkboxGrid }} mt-4">
                    @foreach([__('Game drives'),__('Hot air balloon safari'),__('Boat safari'),__('Cultural visits'),__('Nature walks'),__('Photography'),__('Hiking'),__('Beach relaxation'),__('Night game drive')] as $act)
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-secondary/50 bg-surface/50 px-3 py-2.5 text-sm">
                            <input type="checkbox" name="activities[]" value="{{ $act }}" class="rounded border-secondary text-primary focus:ring-primary" @checked(is_array($actPref) && in_array($act, $actPref, true))>
                            <span>{{ $act }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-8 border-t border-secondary/20 pt-8">
                    <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Special requests') }}</h2>
                    <div class="mt-4">
                        <label class="{{ $label }}" for="special_requests">{{ __('Additional notes') }}</label>
                        <textarea class="{{ $input }} min-h-[120px]" name="special_requests" id="special_requests" rows="5" placeholder="{{ __('Dietary requirements, medical needs, anniversary, honeymoon…') }}">{{ old('special_requests', $pf['special_requests'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Step 6: review & submit --}}
            <div class="mb-8 space-y-8" data-wizard-step="6" x-show="step === 6" x-cloak>
                <div>
                    <h2 class="border-b border-secondary/30 pb-3 text-lg font-semibold text-primary">{{ __('Review & send') }}</h2>
                    <p class="mt-3 text-sm text-ink/75">{{ __('Please confirm everything below matches what you entered. When you submit, we save your request and notify our team.') }}</p>
                </div>

                @php
                    $rvDt = 'text-xs font-medium uppercase tracking-wide text-ink/50';
                    $rvDd = 'mt-1 text-sm font-medium text-ink';
                    $rvDdLong = $rvDd.' whitespace-pre-wrap break-words';
                    $rvBox = 'rounded-xl border border-secondary/40 bg-surface/50 p-5';
                @endphp

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Personal information') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div><dt class="{{ $rvDt }}">{{ __('Full name') }}</dt><dd class="{{ $rvDd }}" x-text="reviewName"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Email') }}</dt><dd class="{{ $rvDd }}" x-text="reviewEmail"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Phone number') }}</dt><dd class="{{ $rvDd }}" x-text="reviewPhone"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Country of residence') }}</dt><dd class="{{ $rvDd }}" x-text="reviewCountry"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Preferred contact method') }}</dt><dd class="{{ $rvDd }}" x-text="reviewContactMethod"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Travel dates') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Arrival and departure') }}</dt><dd class="{{ $rvDd }}" x-text="reviewDates"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Flexible dates') }}</dt><dd class="{{ $rvDd }}" x-text="reviewFlexible"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Trip duration') }}</dt><dd class="{{ $rvDd }}" x-text="reviewTripDuration"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Number of travelers') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Party') }}</dt><dd class="{{ $rvDd }}" x-text="reviewParty"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Ages of children') }}</dt><dd class="{{ $rvDd }}" x-text="reviewChildrenAges"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Group type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewGroupType"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Destinations & experience') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Destination preferences') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewDestinations"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Other destination') }}</dt><dd class="{{ $rvDd }}" x-text="reviewOtherDestination"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Type of safari experience') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewExperiences"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Stay, transport & budget') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div><dt class="{{ $rvDt }}">{{ __('Accommodation') }}</dt><dd class="{{ $rvDd }}" x-text="reviewAccommodation"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Room type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewRoom"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Transport type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewTransport"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Airport pickup') }}</dt><dd class="{{ $rvDd }}" x-text="reviewAirportPickup"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Budget range') }}</dt><dd class="{{ $rvDd }}" x-text="reviewBudget"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Activities & interests') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Selected activities') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewActivities"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="mb-4 text-sm font-semibold text-primary">{{ __('Special requests') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Additional notes') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewSpecialRequests"></dd></div>
                    </dl>
                </div>
                <div class="rounded-xl border border-secondary/40 bg-surface/50 p-6">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="consent_privacy" value="1" class="mt-1 rounded border-secondary text-primary focus:ring-primary" @checked(old('consent_privacy')) required>
                        <span class="text-sm text-ink/90">
                            {{ __('I agree to the') }}
                            <a href="{{ $privacyUrl ?? route('privacy') }}" class="font-medium text-primary underline underline-offset-2">{{ __('Privacy Policy') }}</a>.
                            <span class="text-red-600">*</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-2 flex flex-col gap-4 border-t border-secondary/30 pt-8 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                <button
                    type="button"
                    class="order-2 inline-flex min-h-[3rem] items-center justify-center rounded-xl border border-secondary/60 bg-white px-6 py-3 text-sm font-semibold text-ink transition hover:border-primary/40 hover:bg-surface/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:order-1"
                    x-show="step > 1"
                    x-cloak
                    @click="prev()"
                >{{ __('Back') }}</button>
                <p class="order-1 text-center text-sm text-ink/60 sm:order-2 sm:flex-1">{{ __('Step') }} <span x-text="step"></span> {{ __('of') }} <span x-text="totalSteps"></span></p>
                <div class="order-3 flex flex-col gap-3 sm:ml-auto sm:flex-row">
                    <button
                        type="button"
                        class="inline-flex min-h-[3rem] min-w-[10rem] items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                        x-show="step < totalSteps"
                        x-cloak
                        @click="next()"
                    >{{ __('Next') }}</button>
                    <button
                        x-ref="submitBtn"
                        type="submit"
                        class="inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-soft transition duration-300 ease-out hover:-translate-y-0.5 hover:bg-primary/90 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
                        x-show="step === totalSteps"
                        x-cloak
                    >{{ __('Submit request') }}</button>
                </div>
            </div>
        </form>
        @endunless
    </div>
</div>
@endsection
