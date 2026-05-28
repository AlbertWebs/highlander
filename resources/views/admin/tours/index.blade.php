@extends('layouts.admin')

@section('title', __('Tours'))
@section('heading', __('Tours'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Tours') }}
@endsection

@section('content')
<div class="w-full min-w-0 max-w-full">
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <form method="get" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('Search title or slug') }}…" class="min-w-[12rem] flex-1 rounded-xl border border-secondary/50 bg-white px-4 py-2 text-sm sm:max-w-md">
        <label class="sr-only" for="tours-per-page">{{ __('Rows per page') }}</label>
        <select id="tours-per-page" name="per_page" class="rounded-xl border border-secondary/50 bg-white px-3 py-2 text-sm" onchange="this.form.submit()">
            @foreach([10, 15, 25, 30, 50, 100] as $n)
                <option value="{{ $n }}" @selected((int) ($perPage ?? 30) === $n)>{{ $n }} {{ __('per page') }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white">{{ __('Filter') }}</button>
        @if(filled($q))
            <a href="{{ route('admin.tours.index', request()->except('q')) }}" class="rounded-xl border border-secondary/50 px-4 py-2 text-sm font-medium text-ink hover:bg-secondary/30">{{ __('Clear search') }}</a>
        @endif
    </form>
    <a href="{{ route('admin.tours.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add tour') }}</a>
</div>
@if(filled($q))
    <p class="mb-3 text-sm text-ink/65">{{ __('Showing tours matching ":q".', ['q' => $q]) }}</p>
@endif
@php
    $pageTours = $tours->getCollection();
    $groupedTours = collect();
    foreach (($safariStyles ?? collect()) as $style) {
        $styleTours = $pageTours->filter(fn ($tour) => $tour->safariExperiences->contains('id', $style->id))->values();
        if ($styleTours->isNotEmpty()) {
            $groupedTours->push(['label' => $style->title, 'tours' => $styleTours]);
        }
    }
    $unassignedTours = $pageTours->filter(fn ($tour) => $tour->safariExperiences->isEmpty())->values();
    if ($unassignedTours->isNotEmpty()) {
        $groupedTours->push(['label' => __('Unassigned to safari'), 'tours' => $unassignedTours]);
    }
@endphp

@if($pageTours->isNotEmpty())
    <div class="space-y-6">
        @foreach($groupedTours as $group)
            <section>
                <h3 class="mb-2 text-sm font-semibold text-ink">{{ $group['label'] }} <span class="text-ink/50">({{ $group['tours']->count() }})</span></h3>
                <div class="max-w-full overflow-x-auto rounded-2xl border border-secondary/50 bg-white shadow-soft [-webkit-overflow-scrolling:touch]">
                    <table class="w-full min-w-0 table-auto divide-y divide-secondary/40 text-xs sm:text-sm">
                        <thead class="bg-secondary/30 text-left text-[0.65rem] font-semibold uppercase leading-tight text-ink/70 sm:text-xs">
                            <tr>
                                <th class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">{{ __('ID') }}</th>
                                <th class="min-w-0 px-2 py-2 sm:px-3 sm:py-3">{{ __('Title') }}</th>
                                <th class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">{{ __('Menus') }}</th>
                                <th class="hidden min-w-0 px-2 py-2 md:table-cell md:px-3 md:py-3">{{ __('Hub') }}</th>
                                <th class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">{{ __('Status') }}</th>
                                <th class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">{{ __('Itin.') }}</th>
                                <th class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">{{ __('Updated') }}</th>
                                <th class="whitespace-nowrap px-2 py-2 text-right sm:px-3 sm:py-3">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary/30">
                            @foreach($group['tours'] as $tour)
                                <tr class="transition-colors duration-200 ease-out hover:bg-secondary/20">
                    <td class="whitespace-nowrap px-2 py-2 tabular-nums text-ink/80 sm:px-3 sm:py-3">{{ $tour->id }}</td>
                    <td class="max-w-[11rem] px-2 py-2 font-medium text-primary sm:max-w-[18rem] sm:px-3 sm:py-3 md:max-w-xs lg:max-w-md">
                        <span class="line-clamp-2 break-words" title="{{ $tour->title }}">{{ $tour->title }}</span>
                    </td>
                    <td class="px-2 py-2 align-top sm:px-3 sm:py-3">
                        <form method="post" action="{{ route('admin.tours.updateMenus', $tour) }}" class="space-y-1.5 sm:space-y-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[0.65rem]" title="{{ __('Safari, Mountains, Explore Africa') }}">
                                <label class="inline-flex cursor-pointer items-center gap-1 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_safari" value="1" @checked($tour->nav_safari) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>S</span>
                                </label>
                                <label class="inline-flex cursor-pointer items-center gap-1 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_mountain_safari" value="1" @checked($tour->nav_mountain_safari) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>M</span>
                                </label>
                                <label class="inline-flex cursor-pointer items-center gap-1 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_explore_africa" value="1" @checked($tour->nav_explore_africa) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>E</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full min-w-[4.25rem] rounded-lg bg-primary px-1.5 py-1 text-[0.6rem] font-semibold uppercase tracking-wide text-white shadow-sm hover:bg-primary/90 sm:px-2 sm:text-[0.65rem]">{{ __('Save') }}</button>
                        </form>
                    </td>
                    <td class="hidden max-w-[9rem] px-2 py-2 text-ink/70 md:table-cell md:px-3 md:py-3">
                        @if($tour->mountain)
                            <span class="block truncate text-[0.65rem] uppercase tracking-wide text-ink/45" title="{{ $tour->mountain->name }}">{{ __('Mountain') }}</span>
                            <span class="block truncate text-xs" title="{{ $tour->mountain->name }}">{{ $tour->mountain->name }}</span>
                        @endif
                        @if($tour->destination)
                            <span class="{{ $tour->mountain ? 'mt-1.5 block' : '' }} truncate text-[0.65rem] uppercase tracking-wide text-ink/45" title="{{ $tour->destination->name }}">{{ __('Destination') }}</span>
                            <span class="block truncate text-xs" title="{{ $tour->destination->name }}">{{ $tour->destination->name }}</span>
                        @endif
                        @if(!$tour->mountain && !$tour->destination)
                            <span class="text-ink/40">—</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">
                        <form method="POST" action="{{ route('admin.tours.toggle', $tour) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-full px-2 py-1 text-[0.65rem] font-semibold sm:px-3 sm:text-xs {{ $tour->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200 text-slate-600' }}" title="{{ $tour->is_active ? __('Active') : __('Inactive') }}">
                                {{ $tour->is_active ? __('On') : __('Off') }}
                            </button>
                        </form>
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 sm:px-3 sm:py-3">
                        @if(($tour->itinerary_days_count ?? 0) > 0)
                            <span class="tabular-nums text-ink/80">{{ $tour->itinerary_days_count }}d</span>
                        @else
                            <span class="text-ink/45">-</span>
                        @endif
                        <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="ml-1 text-primary hover:underline sm:ml-2" title="{{ __('Manage itinerary') }}">{{ __('Itin.') }}</a>
                    </td>
                    <td class="whitespace-nowrap px-2 py-2 text-ink/60 sm:px-3 sm:py-3">{{ $tour->updated_at->format('Y-m-d') }}</td>
                    <td class="whitespace-nowrap px-2 py-2 text-right sm:px-3 sm:py-3">
                        <a href="{{ route('admin.tours.show', $tour) }}" class="text-primary hover:underline">{{ __('View') }}</a>
                        <a href="{{ route('admin.tours.edit', $tour) }}" class="ml-1 text-primary hover:underline sm:ml-2">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete this tour?')));">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-1 text-red-600 hover:underline sm:ml-2">{{ __('Del') }}</button>
                        </form>
                    </td>
                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>
@else
    <div class="max-w-full overflow-x-auto rounded-2xl border border-secondary/50 bg-white shadow-soft [-webkit-overflow-scrolling:touch]">
        <table class="w-full min-w-0 table-auto divide-y divide-secondary/40 text-xs sm:text-sm">
            <tbody>
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-sm text-ink/60">
                        @if(filled($q))
                            {{ __('No tours match your search.') }}
                            <a href="{{ route('admin.tours.index', request()->except('q')) }}" class="mt-2 inline-block font-semibold text-primary underline">{{ __('Clear search') }}</a>
                        @else
                            {{ __('No tours yet.') }}
                            <a href="{{ route('admin.tours.create') }}" class="mt-2 inline-block font-semibold text-primary underline">{{ __('Add a tour') }}</a>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endif
<div class="mt-6">{{ $tours->links() }}</div>
</div>
@endsection
