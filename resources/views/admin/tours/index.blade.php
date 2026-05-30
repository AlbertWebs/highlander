@extends('layouts.admin')

@section('title', __('Tours'))
@section('heading', __('Tours'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Tours') }}
@endsection

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <form method="get" class="flex gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="{{ __('Search') }}…" class="rounded-xl border border-secondary/50 bg-white px-4 py-2 text-sm">
        <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white">{{ __('Filter') }}</button>
    </form>
    <a href="{{ route('admin.tours.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add tour') }}</a>
</div>
<div class="overflow-x-auto rounded-2xl border border-secondary/50 bg-white shadow-soft">
    <table class="min-w-full divide-y divide-secondary/40 text-sm">
        <thead class="bg-secondary/30 text-left text-xs font-semibold uppercase text-ink/70">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">{{ __('Title') }}</th>
                <th class="px-4 py-3">{{ __('Status') }}</th>
                <th class="px-4 py-3">{{ __('Itinerary') }}</th>
                <th class="px-4 py-3">{{ __('Updated') }}</th>
                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary/30">
            @foreach($tours as $tour)
                <tr
                    class="transition-colors duration-200 ease-out hover:bg-secondary/20"
                    data-aos="fade-up"
                    data-aos-duration="650"
                    data-aos-delay="{{ min(250, 40 * $loop->index) }}"
                >
                    <td class="px-4 py-3">{{ $tour->id }}</td>
                    <td class="px-4 py-3 font-medium text-primary">{{ $tour->title }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.tours.toggle', $tour) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-full px-3 py-1 text-xs font-semibold {{ $tour->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200 text-slate-600' }}">
                                {{ $tour->is_active ? __('Active') : __('Inactive') }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        @if(($tour->itinerary_days_count ?? 0) > 0)
                            <span class="tabular-nums text-ink/80">{{ $tour->itinerary_days_count }} {{ $tour->itinerary_days_count === 1 ? __('day') : __('days') }}</span>
                        @else
                            <span class="text-ink/45">—</span>
                        @endif
                        <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="ml-2 whitespace-nowrap text-primary hover:underline">{{ __('Manage') }}</a>
                    </td>
                    <td class="px-4 py-3 text-ink/60">{{ $tour->updated_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.tours.show', $tour) }}" class="text-primary hover:underline">{{ __('View') }}</a>
                        <a href="{{ route('admin.tours.edit', $tour) }}" class="ml-2 text-primary hover:underline">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.tours.destroy', $tour) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete this tour?')));">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-2 text-red-600 hover:underline">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $tours->links() }}</div>
@endsection
