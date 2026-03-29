@extends('layouts.admin')
@section('title', __('Gallery'))
@section('heading', __('Gallery'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Gallery') }}@endsection
@section('content')
<div class="mb-6 flex justify-between"><form method="get" class="flex gap-2"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"><button class="rounded-xl bg-primary px-4 py-2 text-sm text-white">{{ __('Filter') }}</button></form>
<a href="{{ route('admin.gallery.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add image') }}</a></div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">@foreach($items as $g)<div class="overflow-hidden rounded-xl border bg-white shadow-soft">
    <img src="{{ $g->url }}" alt="" class="aspect-square w-full object-cover">
    <div class="p-3 text-sm flex justify-between items-center">
        <span class="truncate">{{ $g->title ?: '#'.$g->id }}</span>
        <form method="POST" action="{{ route('admin.gallery.toggle', $g) }}">@csrf @method('PATCH')<button class="text-xs text-primary">{{ $g->is_active ? __('On') : __('Off') }}</button></form>
    </div>
    <div class="flex gap-2 border-t p-2"><a href="{{ route('admin.gallery.edit', $g) }}" class="text-xs text-primary">{{ __('Edit') }}</a>
    <form action="{{ route('admin.gallery.destroy', $g) }}" method="post" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-xs text-red-600">{{ __('Delete') }}</button></form></div>
</div>@endforeach</div>
<div class="mt-6">{{ $items->links() }}</div>
@endsection
