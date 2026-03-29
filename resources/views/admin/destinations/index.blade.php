@extends('layouts.admin')
@section('title', __('Destinations'))
@section('heading', __('Destinations'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Destinations') }}@endsection
@section('content')
<div class="mb-6 flex flex-wrap justify-between gap-4">
    <form method="get" class="flex gap-2"><input type="search" name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"><button class="rounded-xl bg-primary px-4 py-2 text-sm text-white">{{ __('Filter') }}</button></form>
    <a href="{{ route('admin.destinations.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add') }}</a>
</div>
<div class="overflow-x-auto rounded-2xl border border-secondary/50 bg-white shadow-soft">
    <table class="min-w-full text-sm"><thead class="bg-secondary/30 text-left text-xs uppercase"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3">{{ __('Date') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
        <tbody class="divide-y divide-secondary/30">@foreach($destinations as $d)<tr><td class="px-4 py-3">{{ $d->id }}</td><td class="px-4 py-3 font-medium text-primary">{{ $d->name }}</td>
            <td class="px-4 py-3"><form method="POST" action="{{ route('admin.destinations.toggle', $d) }}">@csrf @method('PATCH')<button class="rounded-full px-3 py-1 text-xs {{ $d->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200' }}">{{ $d->is_active ? __('Active') : __('Off') }}</button></form></td>
            <td class="px-4 py-3 text-ink/60">{{ $d->updated_at->format('Y-m-d') }}</td>
            <td class="px-4 py-3 text-right space-x-2"><a href="{{ route('admin.destinations.edit', $d) }}" class="text-primary">{{ __('Edit') }}</a><form action="{{ route('admin.destinations.destroy', $d) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form></td></tr>@endforeach</tbody>
    </table>
</div>
<div class="mt-6">{{ $destinations->links() }}</div>
@endsection
