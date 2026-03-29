@extends('layouts.admin')
@section('title', __('Safari Requests'))
@section('heading', __('Safari Requests'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Safari Requests') }}@endsection
@section('content')
<form method="get" class="mb-6 flex flex-wrap items-end gap-3">
    <div>
        <label class="text-xs font-medium text-ink/70">{{ __('Search') }}</label>
        <input name="q" value="{{ $q }}" class="mt-1 block rounded-xl border border-secondary/60 bg-white px-4 py-2 text-sm" placeholder="{{ __('Name, email, phone') }}">
    </div>
    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="dir" value="{{ $dir }}">
    <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white">{{ __('Filter') }}</button>
    @if($q !== '')
        <a href="{{ route('admin.safari-requests.index') }}" class="text-sm text-primary underline">{{ __('Clear') }}</a>
    @endif
</form>

<div class="overflow-x-auto rounded-2xl border border-secondary/50 bg-white shadow-soft">
    <table class="min-w-full text-sm">
        <thead class="bg-secondary/25 text-left text-xs font-semibold uppercase tracking-wide text-ink/70">
            <tr>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'dir' => ($sort === 'id' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">ID</a>
                </th>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'full_name', 'dir' => ($sort === 'full_name' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">{{ __('Customer') }}</a>
                </th>
                <th class="px-4 py-3">{{ __('Email') }}</th>
                <th class="px-4 py-3">{{ __('Phone') }}</th>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'arrival_date', 'dir' => ($sort === 'arrival_date' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">{{ __('Travel') }}</a>
                </th>
                <th class="px-4 py-3">{{ __('Destination') }}</th>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'budget_range', 'dir' => ($sort === 'budget_range' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">{{ __('Budget') }}</a>
                </th>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'dir' => ($sort === 'status' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">{{ __('Status') }}</a>
                </th>
                <th class="px-4 py-3">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => ($sort === 'created_at' && $dir === 'asc') ? 'desc' : 'asc', 'page' => null]) }}" class="hover:text-primary">{{ __('Date') }}</a>
                </th>
                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary/40">
            @forelse($requests as $r)
                @php
                    $statusClass = match ($r->status) {
                        'new' => 'bg-primary/15 text-primary font-medium',
                        'contacted' => 'bg-amber-100 text-amber-900',
                        'quotation_sent' => 'bg-violet-100 text-violet-900',
                        'confirmed' => 'bg-emerald-100 text-emerald-900',
                        'cancelled' => 'bg-slate-200 text-slate-700',
                        default => 'bg-secondary/40 text-ink',
                    };
                @endphp
                <tr class="hover:bg-surface/80">
                    <td class="px-4 py-3 font-mono text-xs">{{ $r->id }}</td>
                    <td class="px-4 py-3 font-medium">{{ $r->full_name }}</td>
                    <td class="px-4 py-3"><a href="mailto:{{ $r->email }}" class="text-primary hover:underline">{{ $r->email }}</a></td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $r->phone }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs">{{ $r->arrival_date->format('Y-m-d') }} → {{ $r->departure_date->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 max-w-[10rem] truncate text-xs">{{ $r->destinationsSummary() }}</td>
                    <td class="px-4 py-3 text-xs">{{ $r->budgetLabel() }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">{{ $r->statusLabel() }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-ink/70">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.safari-requests.show', $r) }}" class="text-primary hover:underline">{{ __('View') }}</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="px-4 py-12 text-center text-ink/60">{{ __('No safari requests yet.') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $requests->links() }}</div>
@endsection
