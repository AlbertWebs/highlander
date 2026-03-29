@extends('layouts.admin')
@section('title', __('Newsletter'))
@section('heading', __('Newsletter subscribers'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Newsletter') }}@endsection
@section('content')
<form method="get" class="mb-6"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"></form>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('Email') }}</th><th class="px-4 py-3">{{ __('Date') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($subscribers as $s)<tr><td class="px-4 py-3">{{ $s->id }}</td><td class="px-4 py-3">{{ $s->email }}</td><td class="px-4 py-3 text-ink/60">{{ $s->created_at->format('Y-m-d') }}</td>
<td class="px-4 py-3 text-right"><form action="{{ route('admin.newsletter-subscribers.destroy', $s) }}" method="post" onsubmit="return confirm(@json(__('Remove?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Remove') }}</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $subscribers->links() }}</div>
@endsection
