@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Plan Your Dream Safari').' - '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@php
    $input = 'form-input-interactive mt-2 w-full rounded-2xl border border-secondary/45 bg-white px-4 py-3.5 text-ink shadow-[0_1px_0_rgba(255,255,255,.9)_inset,0_2px_12px_rgba(46,46,46,.04)] ring-1 ring-black/[0.03] transition duration-200 ease-out placeholder:text-ink/40 focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/20';
    $label = 'block text-[0.68rem] font-semibold uppercase tracking-[0.16em] text-ink/50';
    $stepPane = 'mb-10 rounded-3xl border border-secondary/20 bg-gradient-to-b from-white/98 to-surface/40 p-6 shadow-[0_8px_40px_rgba(46,46,46,.07)] ring-1 ring-primary/[0.06] sm:p-8';
    $checkboxGrid = 'grid gap-3 sm:grid-cols-2 lg:grid-cols-3';
    $choiceCard = 'group flex cursor-pointer items-center gap-3 rounded-2xl border border-secondary/40 bg-white/80 px-4 py-3 text-sm text-ink shadow-sm ring-1 ring-black/[0.02] transition duration-200 hover:border-primary/35 hover:bg-primary/[0.03] hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-gradient-to-br has-[:checked]:from-primary/[0.08] has-[:checked]:to-accent/[0.06] has-[:checked]:ring-primary/15';
    $choiceCardTall = 'group flex cursor-pointer items-start gap-3 rounded-2xl border border-secondary/40 bg-white/80 p-4 text-sm text-ink shadow-sm ring-1 ring-black/[0.02] transition duration-200 hover:border-primary/35 hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-gradient-to-br has-[:checked]:from-primary/[0.08] has-[:checked]:to-accent/[0.06] has-[:checked]:ring-primary/15';
    $stepNavLabels = [__('You'), __('When'), __('Party'), __('Focus'), __('Interests'), __('Send')];
    $pf = $prefill ?? [];
    $destPref = old('destinations', $pf['destinations'] ?? []);
    $expPref = old('experience_types', $pf['experience_types'] ?? []);
    $actPref = old('activities', $pf['activities'] ?? []);
    $wizardStep = $errors->any() ? 1 : (int) old('wizard_step', 1);
    $wizardStep = max(1, min(6, $wizardStep));
@endphp

{{-- Hero: bottom vignette is merged into overlays so light "surface" fades never cover copy --}}
<section class="relative isolate flex min-h-[40vh] flex-col justify-end overflow-hidden pt-32 pb-12 sm:min-h-[44vh] sm:pt-36 sm:pb-16">
    <div
        class="absolute inset-0 z-0 bg-cover bg-center"
        style="background-image:
            linear-gradient(to top, rgba(10,12,12,0.82) 0%, rgba(10,12,12,0.35) 38%, rgba(10,12,12,0) 55%),
            linear-gradient(165deg, rgba(10,12,12,0.88) 0%, rgba(10,12,12,0.55) 45%, rgba(46,90,60,0.32) 100%),
            url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=2000&q=80');"
    ></div>
    <div class="site-gutter-x relative z-10 w-full text-white" data-aos="fade-up" data-aos-duration="900">
        <nav class="mb-6 text-xs font-medium text-white/75" aria-label="{{ __('Breadcrumb') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-white/80 transition hover:text-white">{{ __('Home') }}</a></li>
                <li aria-hidden="true" class="text-white/40">/</li>
                <li class="text-white/95">{{ __('Plan My Safari') }}</li>
            </ol>
        </nav>
        <p class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-white/90 backdrop-blur-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-accent shadow-[0_0_10px_rgba(139,195,74,0.85)]" aria-hidden="true"></span>
            {{ __('Bespoke itinerary') }}
        </p>
        <h1 class="max-w-3xl font-serif text-4xl font-medium leading-[1.12] tracking-tight text-white sm:text-5xl lg:text-[3.25rem]">{{ __('Plan Your Dream Safari') }}</h1>
        <p class="mt-5 max-w-2xl text-lg leading-relaxed !text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.35)]">{{ __('Tell us about your travel plans and we will create the perfect safari experience for you.') }}</p>
    </div>
</section>

<div class="relative bg-gradient-to-b from-surface via-surface to-secondary/20 pb-24 pt-10 sm:pt-16">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent" aria-hidden="true"></div>
    <div class="site-gutter-x relative mx-auto max-w-5xl">
        @if(session('safari_success'))
            <div class="relative mb-10 overflow-hidden rounded-3xl border border-primary/30 bg-white px-6 py-9 shadow-[0_20px_60px_rgba(46,46,46,.1)] ring-1 ring-primary/10 sm:px-10" role="status" aria-live="polite">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary via-accent to-primary" aria-hidden="true"></div>
                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-primary/10 blur-3xl" aria-hidden="true"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-start">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary/80 text-2xl text-white shadow-lg shadow-primary/25" aria-hidden="true">✓</div>
                    <div class="min-w-0 flex-1 space-y-4">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary/90">{{ __('Request received') }}</p>
                        <h2 class="font-serif text-3xl font-semibold tracking-tight text-ink">{{ __('We received your information') }}</h2>
                        <p class="max-w-2xl text-base leading-relaxed text-ink/80">{{ __('Thank you for completing the safari planner. Your details are saved and our team has been notified. We will get in touch using the contact information you provided.') }}</p>
                        @if(session('safari_request_id'))
                            <p class="inline-flex max-w-full flex-wrap items-baseline gap-2 rounded-2xl border border-secondary/30 bg-surface/80 px-5 py-3.5 text-sm text-ink/85 ring-1 ring-black/[0.03]">
                                <span class="text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-ink/45">{{ __('Reference') }}</span>
                                <span class="font-mono text-base font-semibold text-primary">#{{ session('safari_request_id') }}</span>
                            </p>
                        @endif
                        <a href="{{ route('plan-my-safari', array_filter(request()->only(['tour', 'mountain', 'destination', 'safari']))) }}" class="inline-flex min-h-[2.85rem] items-center justify-center rounded-2xl border border-primary/35 bg-white px-6 py-3 text-sm font-semibold text-primary shadow-sm transition hover:border-primary hover:bg-primary/[0.04] focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">{{ __('Plan another safari') }}</a>
                    </div>
                </div>
            </div>
        @endif

        @if(! empty($prefillTour))
            <div class="relative mb-8 overflow-hidden rounded-3xl border border-primary/25 bg-gradient-to-br from-white to-primary/[0.06] p-6 shadow-[0_8px_36px_rgba(46,46,46,.06)] ring-1 ring-primary/[0.08] sm:p-7" role="status">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-primary to-accent" aria-hidden="true"></div>
                <p class="pl-4 text-sm leading-relaxed text-ink/85 sm:pl-5">
                    <span class="font-semibold text-ink">{{ __('Starting from your selection') }}</span>
                    — {{ __('We have pre-filled this form from your selected experience:') }}
                    <span class="font-serif text-base font-semibold text-primary">{{ $prefillTour->title }}</span>.
                    {{ __('Adjust any details before submitting.') }}
                </p>
            </div>
        @endif

        @if(! empty($prefillMountain))
            <div class="relative mb-8 overflow-hidden rounded-3xl border border-secondary/30 bg-gradient-to-br from-white to-tint-green/30 p-6 shadow-[0_8px_36px_rgba(46,46,46,.06)] ring-1 ring-primary/[0.06] sm:p-7" role="status">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-accent to-primary/70" aria-hidden="true"></div>
                <p class="pl-4 text-sm leading-relaxed text-ink/85 sm:pl-5">
                    <span class="font-semibold text-ink">{{ __('Mountain focus') }}</span>
                    — {{ __('You are planning around') }}
                    <span class="font-serif text-base font-semibold text-primary">{{ $prefillMountain->name }}</span>.
                    {{ __('We have noted this in your trip details; adjust anything before you submit.') }}
                </p>
            </div>
        @endif

        @if(! empty($prefillDestination ?? null))
            <div class="relative mb-8 overflow-hidden rounded-3xl border border-primary/25 bg-gradient-to-br from-white to-primary/[0.05] p-6 shadow-[0_8px_36px_rgba(46,46,46,.06)] ring-1 ring-primary/[0.08] sm:p-7" role="status">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-primary to-accent" aria-hidden="true"></div>
                <p class="pl-4 text-sm leading-relaxed text-ink/85 sm:pl-5">
                    <span class="font-semibold text-ink">{{ __('Destination focus') }}</span>
                    — {{ __('You opened the planner from') }}
                    <span class="font-serif text-base font-semibold text-primary">{{ $prefillDestination->name }}</span>.
                    {{ __('We have added this to your trip focus; adjust anything before you submit.') }}
                </p>
            </div>
        @endif

        @if(! empty($prefillSafariExperience ?? null))
            <div class="relative mb-8 overflow-hidden rounded-3xl border border-secondary/30 bg-gradient-to-br from-white to-tint-green/25 p-6 shadow-[0_8px_36px_rgba(46,46,46,.06)] ring-1 ring-primary/[0.06] sm:p-7" role="status">
                <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-accent to-primary/60" aria-hidden="true"></div>
                <p class="pl-4 text-sm leading-relaxed text-ink/85 sm:pl-5">
                    <span class="font-semibold text-ink">{{ __('Safari style') }}</span>
                    — {{ __('You opened the planner from the safari style') }}
                    <span class="font-serif text-base font-semibold text-primary">{{ $prefillSafariExperience->title }}</span>.
                    {{ __('We have noted this in your trip details; adjust anything before you submit.') }}
                </p>
            </div>
        @endif

        @if($errors->any())
            <div
                class="relative mb-10 overflow-hidden rounded-3xl border border-red-200/90 bg-gradient-to-br from-red-50 to-white px-6 py-5 text-sm text-red-950 shadow-[0_8px_32px_rgba(180,40,40,.08)] ring-1 ring-red-100 sm:px-8"
                x-data
                x-init="$nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start' }))"
                role="alert"
            >
                <p class="font-serif text-lg font-semibold text-red-900">{{ __('Please correct the following:') }}</p>
                <ul class="mt-3 space-y-2 border-t border-red-100/80 pt-3">
                    @foreach($errors->all() as $e)
                        <li class="flex gap-2 leading-relaxed">
                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500" aria-hidden="true"></span>
                            <span>{{ $e }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @unless(session('safari_success'))
        <form
            method="post"
            action="{{ route('plan-my-safari.store') }}"
            class="relative overflow-hidden rounded-3xl border border-secondary/35 bg-white shadow-[0_24px_80px_rgba(46,46,46,.1)] ring-1 ring-black/[0.04]"
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

            <div class="pointer-events-none absolute -right-20 -top-28 h-72 w-72 rounded-full bg-primary/[0.07] blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 h-56 w-56 rounded-full bg-accent/[0.09] blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-primary via-accent to-primary" aria-hidden="true"></div>

            <div class="relative px-5 py-9 sm:px-10 sm:py-11">
            <div class="pointer-events-none absolute -left-[9999px] h-px w-px overflow-hidden opacity-0" aria-hidden="true">
                <label for="hp_website">{{ __('Website') }}</label>
                <input type="text" name="hp_website" id="hp_website" value="" tabindex="-1" autocomplete="off">
            </div>

            <div class="mb-10" aria-hidden="false">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p id="wizard-progress-label" class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary/90">{{ __('Plan your safari') }}</p>
                        <p class="mt-1 font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.65rem]">{{ __('Your private brief') }}</p>
                        <p class="mt-2 max-w-xl text-sm leading-relaxed text-ink/65">{{ __('A few short steps — save as you go. We only use this to design your journey.') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-secondary/35 bg-surface/90 px-4 py-2 text-xs font-semibold tabular-nums text-primary shadow-sm" aria-live="polite">
                        <span class="text-ink/50">{{ __('Step') }}</span>
                        <span x-text="`${step} / ${totalSteps}`"></span>
                    </span>
                </div>

                <div class="mt-6 flex gap-2 overflow-x-auto pb-1 sm:gap-2.5">
                    @foreach($stepNavLabels as $idx => $navLabel)
                        @php $n = $idx + 1; @endphp
                        <div class="flex min-w-[4.5rem] flex-1 flex-col items-center gap-2 text-center sm:min-w-0">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-bold transition duration-300"
                                :class="{
                                    'bg-gradient-to-br from-primary to-primary/85 text-white shadow-lg shadow-primary/30 ring-2 ring-primary/20': step === {{ $n }},
                                    'bg-primary/15 text-primary ring-1 ring-primary/25': step > {{ $n }},
                                    'bg-secondary/40 text-ink/45 ring-1 ring-secondary/30': step < {{ $n }}
                                }"
                            >{{ $n }}</div>
                            <span class="hidden text-[0.6rem] font-semibold uppercase leading-tight tracking-[0.08em] text-ink/50 sm:block">{{ $navLabel }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 h-2 overflow-hidden rounded-full bg-secondary/35 shadow-inner">
                    <div class="h-full rounded-full bg-gradient-to-r from-primary via-primary to-accent transition-all duration-500 ease-out motion-reduce:transition-none" :style="`width: ${(step / totalSteps) * 100}%`"></div>
                </div>
            </div>

            {{-- Step 1 --}}
            <div class="{{ $stepPane }}" data-wizard-step="1" x-show="step === 1" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Personal information') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/60">{{ __('How we should address you and the best way to reach you.') }}</p>
                </div>
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
            <div class="{{ $stepPane }}" data-wizard-step="2" x-show="step === 2" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Travel dates') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/60">{{ __('Approximate dates are fine — we will refine availability with you.') }}</p>
                </div>
                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}" for="arrival_date">{{ __('Arrival date') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="date" name="arrival_date" id="arrival_date" x-model="arrival" x-ref="arrivalDate" value="{{ old('arrival_date', $pf['arrival_date'] ?? '') }}" required>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="departure_date">{{ __('Departure date') }} <span class="text-red-600">*</span></label>
                        <input class="{{ $input }}" type="date" name="departure_date" id="departure_date" x-model="departure" x-ref="departureDate" value="{{ old('departure_date', $pf['departure_date'] ?? '') }}" required>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 sm:col-span-2">
                        <label class="inline-flex cursor-pointer items-center gap-3 rounded-2xl border border-secondary/40 bg-white/80 px-4 py-3 text-sm font-medium text-ink shadow-sm ring-1 ring-black/[0.02] transition hover:border-primary/35 has-[:checked]:border-primary has-[:checked]:bg-primary/[0.06]">
                            <input class="rounded border-secondary text-primary focus:ring-primary" type="checkbox" name="flexible_dates" value="1" @checked(old('flexible_dates'))>
                            <span>{{ __('Flexible dates') }}</span>
                        </label>
                    </div>
                    <div class="sm:col-span-2" x-show="tripDays !== null" x-cloak>
                        <div class="rounded-2xl border border-primary/25 bg-gradient-to-r from-primary/[0.08] via-white to-accent/[0.06] px-5 py-4 ring-1 ring-primary/10">
                            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-primary/90">{{ __('Trip length') }}</p>
                            <p class="mt-1 font-serif text-xl font-semibold text-ink">
                                <span x-text="tripDays"></span>
                                <span class="text-base font-sans font-medium text-ink/70">{{ __('days') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="{{ $stepPane }}" data-wizard-step="3" x-show="step === 3" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Number of travelers') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/60">{{ __('Party size and ages help us match vehicles, rooms, and pacing.') }}</p>
                </div>
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
            <div class="{{ $stepPane }}" data-wizard-step="4" x-show="step === 4" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Destination preferences') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ __('Select all that apply') }}</p>
                </div>
                <div class="{{ $checkboxGrid }} mt-6">
                    @foreach(['Maasai Mara','Amboseli','Tsavo','Lake Nakuru','Samburu','Serengeti','Ngorongoro','Zanzibar','Mount Kenya'] as $dest)
                        <label class="{{ $choiceCard }}">
                            <input type="checkbox" name="destinations[]" value="{{ $dest }}" class="rounded border-secondary text-primary focus:ring-primary" @checked(is_array($destPref) && in_array($dest, $destPref, true))>
                            <span class="font-medium">{{ $dest }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-6">
                    <label class="{{ $label }}" for="other_destination">{{ __('Other destination') }}</label>
                    <input class="{{ $input }}" type="text" name="other_destination" id="other_destination" value="{{ old('other_destination', $pf['other_destination'] ?? '') }}" placeholder="{{ __('Specify other place') }}">
                </div>

            <div class="mt-10 border-t border-secondary/15 pt-10">
                <h3 class="font-serif text-lg font-semibold text-ink">{{ __('Type of safari experience') }}</h3>
                <p class="mt-1 text-sm text-ink/60">{{ __('Select all that apply') }}</p>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach([__('Wildlife Safari'),__('Luxury Safari'),__('Budget Safari'),__('Family Safari'),__('Honeymoon Safari'),__('Photography Safari'),__('Adventure Safari'),__('Cultural Experience'),__('Mountain Climbing'),__('Beach Holiday'),__('Bird Watching')] as $exp)
                        <label class="{{ $choiceCardTall }}">
                            <input type="checkbox" name="experience_types[]" value="{{ $exp }}" class="mt-0.5 rounded border-secondary text-primary focus:ring-primary" @checked(is_array($expPref) && in_array($exp, $expPref, true))>
                            <span class="leading-snug">{{ $exp }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 border-t border-secondary/15 pt-10">
                <h3 class="font-serif text-lg font-semibold text-ink">{{ __('Accommodation preferences') }}</h3>
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

            <div class="mt-10 border-t border-secondary/15 pt-10">
                <h3 class="font-serif text-lg font-semibold text-ink">{{ __('Transportation') }}</h3>
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
                    <div class="flex items-end sm:justify-end">
                        <label class="inline-flex w-full cursor-pointer items-center gap-3 rounded-2xl border border-secondary/40 bg-white/80 px-4 py-3.5 text-sm font-medium text-ink shadow-sm ring-1 ring-black/[0.02] transition hover:border-primary/35 has-[:checked]:border-primary has-[:checked]:bg-primary/[0.06] sm:w-auto">
                            <input class="rounded border-secondary text-primary focus:ring-primary" type="checkbox" name="airport_pickup" value="1" @checked(old('airport_pickup'))>
                            <span>{{ __('Airport pickup') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-10 border-t border-secondary/15 pt-10">
                <h3 class="font-serif text-lg font-semibold text-ink">{{ __('Budget range') }}</h3>
                <p class="mt-1 text-sm text-ink/60">{{ __('A rough band is enough for an indicative proposal.') }}</p>
                <div class="mt-5 space-y-3">
                    @foreach([
                        'under_1000' => __('Under $1,000'),
                        '1000_2500' => __('$1,000 – $2,500'),
                        '2500_5000' => __('$2,500 – $5,000'),
                        '5000_plus' => __('$5,000+'),
                        'not_sure' => __('Not sure'),
                    ] as $val => $lab)
                        <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-secondary/40 bg-white/80 px-5 py-4 text-sm shadow-sm ring-1 ring-black/[0.02] transition hover:border-primary/35 hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-gradient-to-r has-[:checked]:from-primary/[0.07] has-[:checked]:to-accent/[0.05] has-[:checked]:ring-primary/15">
                            <input type="radio" name="budget_range" value="{{ $val }}" class="border-secondary text-primary focus:ring-primary" @checked(old('budget_range', $pf['budget_range'] ?? '') === $val)>
                            <span class="font-medium text-ink">{{ $lab }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            </div>

            {{-- Step 5 --}}
            <div class="{{ $stepPane }}" data-wizard-step="5" x-show="step === 5" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Activities & interests') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">{{ __('Select all that apply') }}</p>
                </div>
                <div class="{{ $checkboxGrid }} mt-6">
                    @foreach([__('Game drives'),__('Hot air balloon safari'),__('Boat safari'),__('Cultural visits'),__('Nature walks'),__('Photography'),__('Hiking'),__('Beach relaxation'),__('Night game drive')] as $act)
                        <label class="{{ $choiceCard }}">
                            <input type="checkbox" name="activities[]" value="{{ $act }}" class="rounded border-secondary text-primary focus:ring-primary" @checked(is_array($actPref) && in_array($act, $actPref, true))>
                            <span class="font-medium">{{ $act }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-10 border-t border-secondary/15 pt-10">
                    <h3 class="font-serif text-lg font-semibold text-ink">{{ __('Special requests') }}</h3>
                    <div class="mt-4">
                        <label class="{{ $label }}" for="special_requests">{{ __('Additional notes') }}</label>
                        <textarea class="{{ $input }} min-h-[120px]" name="special_requests" id="special_requests" rows="5" placeholder="{{ __('Dietary requirements, medical needs, anniversary, honeymoon…') }}">{{ old('special_requests', $pf['special_requests'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Step 6: review & submit --}}
            <div class="{{ $stepPane }} !mb-8 space-y-8" data-wizard-step="6" x-show="step === 6" x-cloak>
                <div class="border-b border-secondary/20 pb-5">
                    <h2 class="font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem]">{{ __('Review & send') }}</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink/70">{{ __('Please confirm everything below matches what you entered. When you submit, we save your request and notify our team.') }}</p>
                </div>

                @php
                    $rvDt = 'text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-ink/45';
                    $rvDd = 'mt-1.5 text-sm font-medium text-ink';
                    $rvDdLong = $rvDd.' whitespace-pre-wrap break-words';
                    $rvBox = 'rounded-2xl border border-secondary/25 bg-gradient-to-b from-white/95 to-surface/50 p-6 shadow-[0_4px_24px_rgba(46,46,46,.05)] ring-1 ring-primary/[0.04] sm:p-7';
                    $rvH3 = 'mb-5 border-b border-secondary/15 pb-3 font-serif text-base font-semibold text-ink';
                @endphp

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Personal information') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div><dt class="{{ $rvDt }}">{{ __('Full name') }}</dt><dd class="{{ $rvDd }}" x-text="reviewName"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Email') }}</dt><dd class="{{ $rvDd }}" x-text="reviewEmail"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Phone number') }}</dt><dd class="{{ $rvDd }}" x-text="reviewPhone"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Country of residence') }}</dt><dd class="{{ $rvDd }}" x-text="reviewCountry"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Preferred contact method') }}</dt><dd class="{{ $rvDd }}" x-text="reviewContactMethod"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Travel dates') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Arrival and departure') }}</dt><dd class="{{ $rvDd }}" x-text="reviewDates"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Flexible dates') }}</dt><dd class="{{ $rvDd }}" x-text="reviewFlexible"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Trip duration') }}</dt><dd class="{{ $rvDd }}" x-text="reviewTripDuration"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Number of travelers') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Party') }}</dt><dd class="{{ $rvDd }}" x-text="reviewParty"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Ages of children') }}</dt><dd class="{{ $rvDd }}" x-text="reviewChildrenAges"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Group type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewGroupType"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Destinations & experience') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Destination preferences') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewDestinations"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Other destination') }}</dt><dd class="{{ $rvDd }}" x-text="reviewOtherDestination"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Type of safari experience') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewExperiences"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Stay, transport & budget') }}</h3>
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div><dt class="{{ $rvDt }}">{{ __('Accommodation') }}</dt><dd class="{{ $rvDd }}" x-text="reviewAccommodation"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Room type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewRoom"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Transport type') }}</dt><dd class="{{ $rvDd }}" x-text="reviewTransport"></dd></div>
                        <div><dt class="{{ $rvDt }}">{{ __('Airport pickup') }}</dt><dd class="{{ $rvDd }}" x-text="reviewAirportPickup"></dd></div>
                        <div class="sm:col-span-2"><dt class="{{ $rvDt }}">{{ __('Budget range') }}</dt><dd class="{{ $rvDd }}" x-text="reviewBudget"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Activities & interests') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Selected activities') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewActivities"></dd></div>
                    </dl>
                </div>

                <div class="{{ $rvBox }}">
                    <h3 class="{{ $rvH3 }}">{{ __('Special requests') }}</h3>
                    <dl class="grid gap-4">
                        <div><dt class="{{ $rvDt }}">{{ __('Additional notes') }}</dt><dd class="{{ $rvDdLong }}" x-text="reviewSpecialRequests"></dd></div>
                    </dl>
                </div>
                <div class="rounded-2xl border border-secondary/30 bg-gradient-to-br from-primary/[0.04] via-white to-surface/60 p-6 ring-1 ring-primary/[0.06] sm:p-7">
                    <label class="flex cursor-pointer items-start gap-4">
                        <input type="checkbox" name="consent_privacy" value="1" class="mt-1 rounded border-secondary text-primary focus:ring-primary" @checked(old('consent_privacy')) required>
                        <span class="text-sm leading-relaxed text-ink/85">
                            {{ __('I agree to the') }}
                            <a href="{{ $privacyUrl ?? route('privacy') }}" class="font-semibold text-primary underline underline-offset-2 hover:text-primary/80">{{ __('Privacy Policy') }}</a>.
                            <span class="text-red-600">*</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-5 border-t border-secondary/25 bg-gradient-to-r from-transparent via-secondary/10 to-transparent pt-9 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                <button
                    type="button"
                    class="order-2 inline-flex min-h-[3rem] items-center justify-center rounded-2xl border border-secondary/50 bg-white px-7 py-3 text-sm font-semibold text-ink shadow-sm transition hover:border-primary/35 hover:bg-surface/90 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary sm:order-1"
                    x-show="step > 1"
                    x-cloak
                    @click="prev()"
                >{{ __('Back') }}</button>
                <p class="order-1 text-center text-xs font-semibold uppercase tracking-[0.12em] text-ink/45 sm:order-2 sm:flex-1">{{ __('Step') }} <span class="text-primary tabular-nums" x-text="step"></span> {{ __('of') }} <span class="tabular-nums text-ink/70" x-text="totalSteps"></span></p>
                <div class="order-3 flex flex-col gap-3 sm:ml-auto sm:flex-row sm:gap-4">
                    <button
                        type="button"
                        class="inline-flex min-h-[3rem] min-w-[10.5rem] items-center justify-center rounded-2xl bg-gradient-to-r from-primary via-primary to-accent px-7 py-3 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(46,46,46,.18)] transition hover:brightness-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                        x-show="step < totalSteps"
                        x-cloak
                        @click="next()"
                    >{{ __('Next') }}</button>
                    <button
                        x-ref="submitBtn"
                        type="submit"
                        class="inline-flex min-h-[3rem] min-w-[12rem] items-center justify-center rounded-2xl bg-gradient-to-r from-primary via-primary to-accent px-7 py-3 text-sm font-semibold text-white shadow-[0_14px_40px_rgba(46,46,46,.2)] transition duration-300 ease-out hover:-translate-y-0.5 hover:brightness-110 hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:brightness-100"
                        x-show="step === totalSteps"
                        x-cloak
                    >{{ __('Submit request') }}</button>
                </div>
            </div>
            </div>
        </form>
        @endunless
    </div>
</div>
@endsection
