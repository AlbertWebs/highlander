@extends('layouts.admin')
@section('title', __('Testimonials'))
@section('heading', __('Testimonials'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Testimonials') }}@endsection
@section('content')
<div class="mb-6 flex justify-between"><form method="get" class="flex gap-2"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"><button class="rounded-xl bg-primary px-4 py-2 text-sm text-white">{{ __('Filter') }}</button></form>
<a href="{{ route('admin.testimonials.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add') }}</a></div>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Status') }}</th><th class="px-4 py-3">{{ __('Date') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($testimonials as $t)<tr><td class="px-4 py-3">{{ $t->id }}</td><td class="px-4 py-3 font-medium text-primary">{{ $t->name }}</td>
<td class="px-4 py-3"><form method="POST" action="{{ route('admin.testimonials.toggle', $t) }}">@csrf @method('PATCH')<button class="rounded-full px-3 py-1 text-xs {{ $t->is_active ? 'bg-primary/15 text-primary' : 'bg-slate-200' }}">{{ $t->is_active ? __('Active') : __('Off') }}</button></form></td>
<td class="px-4 py-3 text-ink/60">{{ $t->updated_at->format('Y-m-d') }}</td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.testimonials.edit', $t) }}" class="text-primary">{{ __('Edit') }}</a>
<form action="{{ route('admin.testimonials.destroy', $t) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $testimonials->links() }}</div>
@endsection
