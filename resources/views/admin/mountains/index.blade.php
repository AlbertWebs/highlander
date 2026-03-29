@extends('layouts.admin')
@section('title', __('Mountains'))
@section('heading', __('Mountains'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Mountains') }}@endsection
@section('content')
<div class="mb-6 flex justify-between"><form method="get" class="flex gap-2"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"><button class="rounded-xl bg-primary px-4 py-2 text-sm text-white">{{ __('Filter') }}</button></form>
<a href="{{ route('admin.mountains.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add') }}</a></div>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3">{{ __('Date') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($mountains as $m)<tr><td class="px-4 py-3">{{ $m->id }}</td><td class="font-medium text-primary px-4 py-3">{{ $m->name }}</td>
<td class="px-4 py-3"><form method="POST" action="{{ route('admin.mountains.toggle', $m) }}">@csrf @method('PATCH')<button class="rounded-full px-3 py-1 text-xs {{ $m->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200' }}">{{ $m->is_active ? __('Active') : __('Off') }}</button></form></td>
<td class="px-4 py-3 text-ink/60">{{ $m->updated_at->format('Y-m-d') }}</td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.mountains.edit', $m) }}" class="text-primary">{{ __('Edit') }}</a>
<form action="{{ route('admin.mountains.destroy', $m) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $mountains->links() }}</div>
@endsection
