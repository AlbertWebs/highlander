@extends('layouts.admin')
@section('title', __('Gallery'))
@section('heading', __('Gallery'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Gallery') }}@endsection
@section('content')
<div class="mx-auto max-w-6xl pb-24">
    @include('admin.gallery._dropzone', ['categories' => $categories, 'uploadUrl' => route('admin.gallery.bulk-store')])

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="get" class="flex flex-wrap gap-2">
            <input name="q" value="{{ $q }}" placeholder="{{ __('Search titles') }}…" class="min-w-[12rem] flex-1 rounded-xl border border-secondary/60 px-4 py-2 text-sm sm:max-w-xs">
            <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white">{{ __('Filter') }}</button>
        </form>
        <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center justify-center rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add single image') }}</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($items as $g)
            <div class="overflow-hidden rounded-xl border border-secondary/50 bg-white shadow-soft">
                <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                    <img src="{{ $g->url }}" alt="" class="h-full w-full object-cover">
                </div>
                <div class="flex items-start justify-between gap-2 border-t border-secondary/40 p-3 text-sm">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-ink">{{ $g->title ?: '#'.$g->id }}</p>
                        @if($g->category)
                            <p class="mt-0.5 truncate text-xs text-primary">{{ $g->category->name }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.gallery.toggle', $g) }}">@csrf @method('PATCH')
                        <button type="submit" class="shrink-0 text-xs font-medium text-primary">{{ $g->is_active ? __('On') : __('Off') }}</button>
                    </form>
                </div>
                <div class="flex gap-2 border-t border-secondary/30 p-2">
                    <a href="{{ route('admin.gallery.edit', $g) }}" class="text-xs font-medium text-primary">{{ __('Edit') }}</a>
                    <form action="{{ route('admin.gallery.destroy', $g) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $items->links() }}</div>
</div>
@endsection
