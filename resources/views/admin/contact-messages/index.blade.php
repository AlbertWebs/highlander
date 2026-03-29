@extends('layouts.admin')
@section('title', __('Messages'))
@section('heading', __('Contact messages'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Messages') }}@endsection
@section('content')
<form method="get" class="mb-6"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"></form>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('From') }}</th><th class="px-4 py-3">{{ __('Subject') }}</th><th class="px-4 py-3">{{ __('Read') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($messages as $m)<tr class="{{ $m->read_at ? '' : 'bg-accent/10' }}"><td class="px-4 py-3">{{ $m->id }}</td><td class="px-4 py-3">{{ $m->name }}<br><span class="text-xs text-ink/60">{{ $m->email }}</span></td>
<td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($m->subject ?? '—', 40) }}</td>
<td class="px-4 py-3">{{ $m->read_at ? $m->read_at->format('Y-m-d') : __('Unread') }}</td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.contact-messages.show', $m) }}" class="text-primary">{{ __('Open') }}</a>
<form action="{{ route('admin.contact-messages.destroy', $m) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $messages->links() }}</div>
@endsection
