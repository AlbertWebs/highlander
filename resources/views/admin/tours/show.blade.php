@extends('layouts.admin')

@section('title', $tour->title)
@section('heading', $tour->title)
@section('breadcrumb')
    <a href="{{ route('admin.tours.index') }}">{{ __('Tours') }}</a> / {{ __('Detail') }}
@endsection

@section('content')
<div class="max-w-3xl rounded-2xl border border-secondary/50 bg-white p-8 shadow-soft">
    @if(($tour->featured_media_type ?? 'image') === 'video' && filled($tour->featured_video_url))
        <p class="mb-2 text-xs font-medium uppercase tracking-wide text-ink/55">{{ __('Featured card video') }}</p>
        <p class="mb-6 break-all rounded-xl bg-surface px-3 py-2 font-mono text-sm text-ink/90">{{ $tour->featured_video_url }}</p>
    @elseif($tour->imageUrl())
        <img src="{{ $tour->imageUrl() }}" alt="" class="mb-6 max-h-64 rounded-xl object-cover">
    @endif
    <dl class="grid gap-4 sm:grid-cols-2 text-sm">
        <div><dt class="text-ink/60">{{ __('Price') }}</dt><dd>{{ $tour->price ? '$'.$tour->price : '—' }}</dd></div>
        <div><dt class="text-ink/60">{{ __('Duration') }}</dt><dd>{{ $tour->duration_days ? $tour->duration_days.' '.__('days') : '—' }}</dd></div>
        <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Description') }}</dt><dd class="mt-1 whitespace-pre-wrap">{{ $tour->description }}</dd></div>
    </dl>

    <div class="mt-10 border-t border-secondary/40 pt-8">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-ink/70">{{ __('Itinerary') }}</h2>
            <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="rounded-xl bg-primary/10 px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/15">{{ __('Manage itinerary') }}</a>
        </div>
        @if($tour->itineraryDays->isEmpty())
            <p class="text-sm text-ink/55">{{ __('No itinerary days yet.') }}</p>
        @else
            <ol class="space-y-4 text-sm">
                @foreach($tour->itineraryDays as $day)
                    <li class="overflow-hidden rounded-xl border border-secondary/40 bg-surface/50">
                        @if(filled($day->image))
                            <div class="aspect-[21/9] max-h-52 w-full overflow-hidden bg-secondary/30 sm:aspect-[3/1] sm:max-h-none">
                                <img src="{{ $day->imageUrl() }}" alt="" class="h-full w-full object-cover">
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="font-semibold text-primary">{{ __('Day') }} {{ $day->day_number }} — {{ $day->title }}</p>
                            @if(filled($day->body))
                                <p class="mt-2 whitespace-pre-wrap text-ink/85">{{ $day->body }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('admin.tours.edit', $tour) }}" class="rounded-xl bg-primary px-4 py-2 text-white">{{ __('Edit') }}</a>
        <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="rounded-xl border border-primary/40 bg-primary/5 px-4 py-2 font-medium text-primary">{{ __('Itinerary') }}</a>
        <a href="{{ route('admin.tours.index') }}" class="rounded-xl border border-secondary/50 px-4 py-2">{{ __('Back') }}</a>
    </div>
</div>
@endsection
