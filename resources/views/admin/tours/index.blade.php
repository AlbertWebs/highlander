@extends('layouts.admin')

@section('title', __('Tours'))
@section('heading', __('Tours'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Tours') }}
@endsection

@section('content')
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
<div class="w-full min-w-0 rounded-2xl border border-secondary/50 bg-white shadow-soft">
    <table class="w-full min-w-0 table-fixed divide-y divide-secondary/40 text-sm">
        <thead class="bg-secondary/30 text-left text-xs font-semibold uppercase text-ink/70">
            <tr>
                <th class="w-14 px-3 py-3">ID</th>
                <th class="px-3 py-3">{{ __('Title') }}</th>
                <th class="w-[11.5rem] px-3 py-3">{{ __('Menus') }}</th>
                <th class="w-36 px-3 py-3">{{ __('Hub') }}</th>
                <th class="w-28 px-3 py-3">{{ __('Status') }}</th>
                <th class="w-32 px-3 py-3">{{ __('Itinerary') }}</th>
                <th class="w-28 px-3 py-3">{{ __('Updated') }}</th>
                <th class="w-40 px-3 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary/30">
            @forelse($tours as $tour)
                <tr class="transition-colors duration-200 ease-out hover:bg-secondary/20">
                    <td class="truncate px-3 py-3 tabular-nums">{{ $tour->id }}</td>
                    <td class="px-3 py-3 font-medium text-primary">
                        <span class="line-clamp-2 break-words" title="{{ $tour->title }}">{{ $tour->title }}</span>
                    </td>
                    <td class="px-3 py-3 align-top">
                        <form method="post" action="{{ route('admin.tours.updateMenus', $tour) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-col gap-1 text-[0.7rem] sm:flex-row sm:flex-wrap sm:gap-x-2 sm:gap-y-1" title="{{ __('Safari, Mountains, Explore Africa') }}">
                                <label class="inline-flex cursor-pointer items-center gap-1.5 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_safari" value="1" @checked($tour->nav_safari) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>S</span>
                                </label>
                                <label class="inline-flex cursor-pointer items-center gap-1.5 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_mountain_safari" value="1" @checked($tour->nav_mountain_safari) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>M</span>
                                </label>
                                <label class="inline-flex cursor-pointer items-center gap-1.5 font-medium text-ink/85">
                                    <input type="checkbox" name="nav_explore_africa" value="1" @checked($tour->nav_explore_africa) class="rounded border-secondary text-primary focus:ring-primary">
                                    <span>E</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-primary px-2 py-1 text-[0.65rem] font-semibold uppercase tracking-wide text-white shadow-sm hover:bg-primary/90">{{ __('Save') }}</button>
                        </form>
                    </td>
                    <td class="px-3 py-3 text-ink/70">
                        @if($tour->mountain)
                            <span class="block text-xs uppercase tracking-wide text-ink/45">{{ __('Mountain') }}</span>
                            <span class="text-sm">{{ $tour->mountain->name }}</span>
                        @endif
                        @if($tour->destination)
                            <span class="{{ $tour->mountain ? 'mt-2 block' : '' }} text-xs uppercase tracking-wide text-ink/45">{{ __('Destination') }}</span>
                            <span class="text-sm">{{ $tour->destination->name }}</span>
                        @endif
                        @if(!$tour->mountain && !$tour->destination)
                            <span class="text-ink/40">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-3">
                        <form method="POST" action="{{ route('admin.tours.toggle', $tour) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-full px-3 py-1 text-xs font-semibold {{ $tour->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200 text-slate-600' }}">
                                {{ $tour->is_active ? __('Active') : __('Inactive') }}
                            </button>
                        </form>
                    </td>
                    <td class="px-3 py-3">
                        @if(($tour->itinerary_days_count ?? 0) > 0)
                            <span class="tabular-nums text-ink/80">{{ $tour->itinerary_days_count }} {{ $tour->itinerary_days_count === 1 ? __('day') : __('days') }}</span>
                        @else
                            <span class="text-ink/45">-</span>
                        @endif
                        <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="ml-2 whitespace-nowrap text-primary hover:underline">{{ __('Manage') }}</a>
                    </td>
                    <td class="px-3 py-3 text-ink/60">{{ $tour->updated_at->format('Y-m-d') }}</td>
                    <td class="px-3 py-3 text-right">
                        <a href="{{ route('admin.tours.show', $tour) }}" class="text-primary hover:underline">{{ __('View') }}</a>
                        <a href="{{ route('admin.tours.edit', $tour) }}" class="ml-2 text-primary hover:underline">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete this tour?')));">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-2 text-red-600 hover:underline">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
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
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $tours->links() }}</div>
@endsection
