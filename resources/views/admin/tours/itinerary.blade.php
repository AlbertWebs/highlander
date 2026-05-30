@extends('layouts.admin')

@php
    $initialDays = $tour->itineraryDays->map(function ($d) {
        $path = $d->image;

        return [
            'title' => $d->title,
            'body' => (string) ($d->body ?? ''),
            'existing_image' => $path ?? '',
            'image_preview' => filled($path) ? \Illuminate\Support\Facades\Storage::disk('public')->url($path) : '',
        ];
    })->values()->all();
@endphp

@section('title', __('Tour itinerary'))
@section('heading', __('Tour itinerary'))
@section('breadcrumb')
    <a href="{{ route('admin.tours.index') }}">{{ __('Tours') }}</a>
    /
    <a href="{{ route('admin.tours.show', $tour) }}">{{ $tour->title }}</a>
    /
    {{ __('Itinerary') }}
@endsection

@section('content')
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-primary/35 bg-primary/10 px-4 py-3 text-sm font-medium text-primary" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <p class="font-semibold">{{ __('Please fix the following:') }}</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 max-w-4xl rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <p class="text-sm leading-relaxed text-ink/75">
            {{ __('Add one block per day (or segment). Only rows with a title are saved. Order is preserved: first row is day 1, and so on. You can attach an optional photo for each day.') }}
        </p>
    </div>

    <form
        action="{{ route('admin.tours.itinerary.update', $tour) }}"
        method="post"
        enctype="multipart/form-data"
        class="max-w-4xl space-y-6"
        x-data="tourItineraryEditor(@js($initialDays))"
    >
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <template x-if="days.length === 0">
                <div class="rounded-2xl border border-dashed border-secondary/60 bg-secondary/10 px-6 py-10 text-center text-sm text-ink/65">
                    {{ __('No itinerary days yet. Use “Add day” to create the first one.') }}
                </div>
            </template>

            <template x-for="(day, index) in days" :key="day._id">
                <div class="rounded-2xl border border-secondary/50 bg-white p-5 shadow-soft sm:p-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-primary" x-text="'{{ __('Day') }} ' + (index + 1)"></span>
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-secondary/50 px-3 py-1.5 text-xs font-medium text-ink transition hover:bg-secondary/30 disabled:cursor-not-allowed disabled:opacity-40"
                                @click="moveUp(index)"
                                x-bind:disabled="index === 0"
                            >{{ __('Up') }}</button>
                            <button
                                type="button"
                                class="rounded-lg border border-secondary/50 px-3 py-1.5 text-xs font-medium text-ink transition hover:bg-secondary/30 disabled:cursor-not-allowed disabled:opacity-40"
                                @click="moveDown(index)"
                                x-bind:disabled="index === days.length - 1"
                            >{{ __('Down') }}</button>
                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-50"
                                @click="removeDay(index)"
                            >{{ __('Remove') }}</button>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-ink" x-bind:for="'it-title-' + index">{{ __('Title') }} <span class="text-red-600">*</span></label>
                            <input
                                type="text"
                                class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                                x-bind:id="'it-title-' + index"
                                x-bind:name="'days[' + index + '][title]'"
                                x-model="day.title"
                                maxlength="255"
                                placeholder="{{ __('e.g. Arrival in Nairobi') }}"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-ink" x-bind:for="'it-body-' + index">{{ __('Description') }}</label>
                            <textarea
                                class="mt-1 min-h-[100px] w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                                x-bind:id="'it-body-' + index"
                                x-bind:name="'days[' + index + '][body]'"
                                x-model="day.body"
                                rows="4"
                                placeholder="{{ __('Activities, meals, lodging, transfers…') }}"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-ink">{{ __('Day photo') }}</label>
                            <p class="mt-0.5 text-xs text-ink/55">{{ __('Optional. JPEG, PNG, WebP or GIF — max 5 MB.') }}</p>
                            <input type="hidden" x-bind:name="'days[' + index + '][existing_image]'" x-model="day.existing_image">
                            <input
                                type="file"
                                x-bind:name="'days[' + index + '][image]'"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                                class="mt-2 block w-full cursor-pointer text-sm text-ink/80 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/15"
                                @change="onItineraryImageChange($event, day)"
                            >
                            <div x-show="day.previewUrl" x-transition class="mt-3 overflow-hidden rounded-xl border border-secondary/40 bg-surface/60">
                                <p class="border-b border-secondary/30 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide text-ink/45">{{ __('Preview') }}</p>
                                <div class="p-3">
                                    <img :src="day.previewUrl" alt="" class="max-h-48 w-full rounded-lg object-cover sm:max-h-52">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex flex-wrap gap-3">
            <button
                type="button"
                class="rounded-xl border border-secondary/50 bg-white px-5 py-2.5 text-sm font-semibold text-ink transition hover:bg-secondary/20"
                @click="addDay()"
            >{{ __('Add day') }}</button>
        </div>

        <div class="flex flex-wrap gap-3 border-t border-secondary/30 pt-6">
            <button type="submit" class="rounded-xl bg-primary px-6 py-3 font-semibold text-white shadow-soft hover:bg-primary/90">{{ __('Save itinerary') }}</button>
            <a href="{{ route('admin.tours.index') }}" class="rounded-xl border border-secondary/50 px-6 py-3 font-medium">{{ __('Back to tours') }}</a>
            <a href="{{ route('admin.tours.edit', $tour) }}" class="rounded-xl border border-secondary/50 px-6 py-3 font-medium">{{ __('Edit tour') }}</a>
        </div>
    </form>
@endsection
