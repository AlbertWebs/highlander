@extends('layouts.admin')
@section('title', __('SEO'))
@section('heading', __('SEO metadata'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('SEO') }}@endsection
@section('content')
<div class="mb-6 flex justify-between"><form method="get"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"></form>
<a href="{{ route('admin.seo.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add') }}</a></div>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">{{ __('Page') }}</th><th class="px-4 py-3">{{ __('Title') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($items as $row)<tr><td class="px-4 py-3 font-mono text-xs">{{ $row->page_key }}</td><td class="px-4 py-3">{{ $row->meta_title ?? '—' }}</td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.seo.edit', $row) }}" class="text-primary">{{ __('Edit') }}</a>
<form action="{{ route('admin.seo.destroy', $row) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $items->links() }}</div>
@endsection
