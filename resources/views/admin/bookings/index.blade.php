@extends('layouts.admin')
@section('title', __('Bookings'))
@section('heading', __('Bookings'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Bookings') }}
@endsection
@section('content')
<div class="mb-6 flex flex-wrap gap-4">
    <form method="get" class="flex flex-wrap gap-2">
        <input name="q" value="{{ $q }}" placeholder="{{ __('Search') }}" class="rounded-xl border px-4 py-2 text-sm">
        <select name="status" class="rounded-xl border px-4 py-2 text-sm">
            <option value="">{{ __('All statuses') }}</option>
            @foreach(['pending','confirmed','cancelled','completed'] as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm text-white">{{ __('Filter') }}</button>
    </form>
</div>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft">
    <table class="min-w-full text-sm">
        <thead class="bg-secondary/30 text-left">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">{{ __('Guest') }}</th>
                <th class="px-4 py-3">{{ __('Status') }}</th>
                <th class="px-4 py-3">{{ __('Date') }}</th>
                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary/30">
            @foreach($bookings as $b)
                <tr>
                    <td class="px-4 py-3">{{ $b->id }}</td>
                    <td class="px-4 py-3">{{ $b->name }}<br><span class="text-xs text-ink/60">{{ $b->email }}</span></td>
                    <td class="px-4 py-3"><span class="rounded-full bg-secondary/50 px-3 py-1 text-xs">{{ $b->status }}</span></td>
                    <td class="px-4 py-3 text-ink/60">{{ $b->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.bookings.show', $b) }}" class="text-primary">{{ __('View') }}</a>
                        <a href="{{ route('admin.bookings.edit', $b) }}" class="ml-2 text-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.bookings.destroy', $b) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $bookings->links() }}</div>
@endsection
