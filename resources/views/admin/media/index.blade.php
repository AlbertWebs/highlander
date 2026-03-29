@extends('layouts.admin')
@section('title', __('Media Manager'))
@section('heading', __('Media Manager'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Media') }}@endsection
@section('content')
<div class="mb-6 flex flex-wrap justify-between gap-4">
    <form method="get"><input name="q" value="{{ $q }}" placeholder="{{ __('Search') }}" class="rounded-xl border px-4 py-2 text-sm"></form>
    <form action="{{ route('admin.media.store') }}" method="post" enctype="multipart/form-data" class="flex flex-wrap items-end gap-2" x-data>
        @csrf
        <div>
            <label class="text-xs text-ink/60">{{ __('Upload images') }}</label>
            <input type="file" name="files[]" multiple accept="image/*" class="block text-sm">
        </div>
        <button type="submit" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Upload') }}</button>
    </form>
</div>
<div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
    @foreach($items as $m)
        <div class="group relative overflow-hidden rounded-xl border bg-white shadow-soft">
            <img src="{{ $m->url() }}" alt="" class="aspect-square w-full object-cover" loading="lazy">
            <div class="p-2 text-xs text-ink/70 truncate">{{ $m->original_name }}</div>
            <form action="{{ route('admin.media.destroy', $m) }}" method="post" class="absolute right-2 top-2 opacity-0 transition group-hover:opacity-100" onsubmit="return confirm(@json(__('Delete?')));">
                @csrf @method('DELETE')
                <button type="submit" class="rounded bg-red-600 px-2 py-1 text-xs text-white">{{ __('Delete') }}</button>
            </form>
        </div>
    @endforeach
</div>
<div class="mt-6">{{ $items->links() }}</div>
@endsection
