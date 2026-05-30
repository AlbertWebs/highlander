@extends('layouts.admin')
@section('title', __('Gallery'))
@section('heading', __('Gallery'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Gallery') }}@endsection
@section('content')
@php
    $galleryUploadLabels = [
        'edit' => __('Edit'),
        'delete' => __('Delete'),
        'on' => __('On'),
        'off' => __('Off'),
        'deleteConfirm' => __('Delete?'),
        'uploading' => __('Uploading…'),
        'done' => __('Upload complete'),
    ];
@endphp
<div class="mx-auto max-w-7xl space-y-10 pb-24">
    {{-- Purpose: what this page is for --}}
    <div class="overflow-hidden rounded-2xl border border-secondary/50 bg-gradient-to-br from-white via-white to-tint-green/30 shadow-soft">
        <div class="border-b border-secondary/40 bg-primary/[0.04] px-5 py-4 sm:px-8 sm:py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary">{{ __('Public website') }}</p>
                    <h2 class="mt-1 font-serif text-xl font-semibold text-ink sm:text-2xl">{{ __('Photo gallery for visitors') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink/70">
                        {{ __('Images you add here appear on the public gallery for guests to browse. Use categories so travellers can filter by trip type. Turn images off to hide them without deleting.') }}
                        <a href="{{ route('gallery') }}" class="ml-1 inline-flex items-center gap-1 font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary" target="_blank" rel="noopener noreferrer">
                            {{ __('Open public gallery') }}
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </a>
                    </p>
                </div>
                <dl class="flex shrink-0 flex-wrap gap-4 sm:gap-6 lg:flex-col lg:items-end lg:gap-3">
                    <div class="rounded-xl border border-secondary/50 bg-white/80 px-4 py-2 text-right shadow-sm">
                        <dt class="text-[0.65rem] font-semibold uppercase tracking-wider text-ink/50">{{ __('In library') }}</dt>
                        <dd class="text-2xl font-semibold tabular-nums text-ink">{{ number_format($stats['total']) }}</dd>
                    </div>
                    <div class="rounded-xl border border-secondary/50 bg-white/80 px-4 py-2 text-right shadow-sm">
                        <dt class="text-[0.65rem] font-semibold uppercase tracking-wider text-ink/50">{{ __('Visible on site') }}</dt>
                        <dd class="text-2xl font-semibold tabular-nums text-primary">{{ number_format($stats['visible_on_site']) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="px-5 py-4 sm:px-8">
            <ul class="grid gap-3 text-sm text-ink/75 sm:grid-cols-2 lg:grid-cols-3">
                <li class="flex gap-2 rounded-lg bg-surface/80 px-3 py-2">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-[0.65rem] font-bold text-primary">1</span>
                    <span><span class="font-medium text-ink">{{ __('Upload') }}</span> — {{ __('bulk below, or add one image with full details.') }}</span>
                </li>
                <li class="flex gap-2 rounded-lg bg-surface/80 px-3 py-2">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-[0.65rem] font-bold text-primary">2</span>
                    <span><span class="font-medium text-ink">{{ __('Categorize') }}</span> — {{ __('optional titles and categories help the public filter.') }}</span>
                </li>
                <li class="flex gap-2 rounded-lg bg-surface/80 px-3 py-2 sm:col-span-2 lg:col-span-1">
                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/15 text-[0.65rem] font-bold text-primary">3</span>
                    <span><span class="font-medium text-ink">{{ __('Publish') }}</span> — {{ __('use On/Off to show or hide an image on the public gallery.') }}</span>
                </li>
            </ul>
        </div>
    </div>

    @include('admin.gallery._dropzone', [
        'categories' => $categories,
        'uploadUrl' => route('admin.gallery.bulk-store'),
        'adminGalleryBase' => url('/admin/gallery'),
        'labels' => $galleryUploadLabels,
    ])

    {{-- Library --}}
    <section class="space-y-5" aria-labelledby="gallery-library-heading">
        <div class="flex flex-col gap-4 border-b border-secondary/40 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 id="gallery-library-heading" class="text-lg font-semibold text-ink">{{ __('Your gallery images') }}</h2>
                <p class="mt-1 text-sm text-ink/60">
                    @if($items->total() === 0)
                        {{ __('No images yet — upload above or add a single image.') }}
                    @else
                        {{ __(':count images in this list', ['count' => $items->total()]) }}
                        @if(filled($q))
                            {{ __('matching “:q”.', ['q' => $q]) }}
                        @endif
                    @endif
                </p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="get" class="flex flex-wrap items-center gap-2" role="search">
                    <label class="sr-only" for="gallery-search-q">{{ __('Search titles') }}</label>
                    <input
                        id="gallery-search-q"
                        name="q"
                        value="{{ $q }}"
                        placeholder="{{ __('Search by title…') }}"
                        class="min-w-[12rem] flex-1 rounded-xl border border-secondary/60 bg-white px-4 py-2 text-sm shadow-sm sm:max-w-xs"
                    >
                    @if(filled($q))
                        <a href="{{ route('admin.gallery.index') }}" class="rounded-xl border border-secondary/60 px-3 py-2 text-sm font-medium text-ink/70 hover:bg-surface">{{ __('Clear') }}</a>
                    @endif
                    <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90">{{ __('Search') }}</button>
                </form>
                <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center justify-center rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] hover:bg-accent/90">
                    {{ __('Add single image') }}
                </a>
            </div>
        </div>

        <div id="admin-gallery-grid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($items as $g)
                <article class="group overflow-hidden rounded-xl border border-secondary/50 bg-white shadow-soft transition hover:border-primary/25 hover:shadow-md">
                    <div class="relative aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="{{ $g->url }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]" loading="lazy" width="400" height="300">
                        <span class="absolute left-2 top-2 rounded-md px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-wide shadow-sm {{ $g->is_active ? 'bg-primary text-white' : 'bg-ink/70 text-white' }}">
                            {{ $g->is_active ? __('Visible') : __('Hidden') }}
                        </span>
                    </div>
                    <div class="flex items-start justify-between gap-2 border-t border-secondary/40 p-3 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-ink">{{ $g->title ?: '—' }}</p>
                            <p class="mt-0.5 truncate text-xs text-ink/50">#{{ $g->id }}</p>
                            @if($g->category)
                                <p class="mt-1 truncate text-xs font-medium text-primary">{{ $g->category->name }}</p>
                            @else
                                <p class="mt-1 text-xs text-ink/45">{{ __('Uncategorized') }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.gallery.toggle', $g) }}" class="shrink-0" title="{{ __('Toggle visibility on public gallery') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg border border-secondary/60 bg-white px-2.5 py-1 text-xs font-semibold text-primary transition hover:border-primary/40 hover:bg-primary/5">
                                {{ $g->is_active ? __('On') : __('Off') }}
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-wrap gap-2 border-t border-secondary/30 bg-surface/30 px-3 py-2">
                        <a href="{{ route('admin.gallery.edit', $g) }}" class="text-xs font-semibold text-primary hover:underline">{{ __('Edit') }}</a>
                        <span class="text-ink/25" aria-hidden="true">|</span>
                        <form action="{{ route('admin.gallery.destroy', $g) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:underline">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </article>
            @empty
                <div
                    data-gallery-empty
                    class="col-span-full rounded-2xl border border-dashed border-secondary/70 bg-white px-8 py-16 text-center shadow-inner"
                >
                    @if(filled($q) && $items->total() === 0 && $stats['total'] > 0)
                        <p class="font-medium text-ink">{{ __('No images match your search') }}</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-ink/65">{{ __('Try a different word, or clear the search to see all images.') }}</p>
                        <a href="{{ route('admin.gallery.index') }}" class="mt-6 inline-flex rounded-xl border border-secondary/60 bg-white px-5 py-2.5 text-sm font-semibold text-ink shadow-sm hover:bg-surface">
                            {{ __('Clear search') }}
                        </a>
                    @else
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3A1.5 1.5 0 001.5 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <p class="mt-4 font-medium text-ink">{{ __('This library is empty') }}</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-ink/65">
                            {{ __('Drag photos into the upload area above, or add one image at a time to set title, alt text, and sort order.') }}
                        </p>
                        <a href="{{ route('admin.gallery.create') }}" class="mt-6 inline-flex rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90">
                            {{ __('Add your first image') }}
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($items->isNotEmpty() && $items->hasPages())
            <div class="flex flex-col gap-3 border-t border-secondary/40 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-ink/60">
                    {{ __('Showing :from–:to of :total', ['from' => $items->firstItem(), 'to' => $items->lastItem(), 'total' => $items->total()]) }}
                </p>
                <div>{{ $items->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection
