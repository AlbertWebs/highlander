@extends('layouts.admin')
@section('title', __('Mountains'))
@section('heading', __('Mountains'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Mountains') }}@endsection
@section('content')
<div class="mx-auto max-w-7xl space-y-10 pb-24">
    {{-- Purpose --}}
    <div class="overflow-hidden rounded-2xl border border-secondary/50 bg-gradient-to-br from-white via-white to-tint-green/25 shadow-soft">
        <div class="border-b border-secondary/40 bg-primary/[0.04] px-5 py-4 sm:px-8 sm:py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary">{{ __('Public website') }}</p>
                    <h2 class="mt-1 font-serif text-xl font-semibold text-ink sm:text-2xl">{{ __('Mountain listings for visitors') }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink/70">
                        {{ __('Peaks you manage here appear on the public Mountains page. Inactive rows are hidden from the site but kept for editing.') }}
                        <a href="{{ route('mountains') }}" class="ml-1 inline-flex items-center gap-1 font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary" target="_blank" rel="noopener noreferrer">
                            {{ __('Open public page') }}
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </a>
                    </p>
                </div>
                <dl class="flex shrink-0 flex-wrap gap-4 sm:gap-6 lg:flex-col lg:items-end lg:gap-3">
                    <div class="rounded-xl border border-secondary/50 bg-white/80 px-4 py-2 text-right shadow-sm">
                        <dt class="text-[0.65rem] font-semibold uppercase tracking-wider text-ink/50">{{ __('In admin') }}</dt>
                        <dd class="text-2xl font-semibold tabular-nums text-ink">{{ number_format($stats['total']) }}</dd>
                    </div>
                    <div class="rounded-xl border border-secondary/50 bg-white/80 px-4 py-2 text-right shadow-sm">
                        <dt class="text-[0.65rem] font-semibold uppercase tracking-wider text-ink/50">{{ __('Visible on site') }}</dt>
                        <dd class="text-2xl font-semibold tabular-nums text-primary">{{ number_format($stats['visible']) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <section class="space-y-5" aria-labelledby="mountains-admin-heading">
        <div class="flex flex-col gap-4 border-b border-secondary/40 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 id="mountains-admin-heading" class="text-lg font-semibold text-ink">{{ __('Your mountains') }}</h2>
                <p class="mt-1 text-sm text-ink/60">
                    @if($mountains->total() === 0)
                        {{ __('No mountains yet — add your first peak below.') }}
                    @else
                        {{ __(':count in this list', ['count' => $mountains->total()]) }}
                        @if(filled($q))
                            {{ __('matching “:q”.', ['q' => $q]) }}
                        @endif
                    @endif
                </p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="get" class="flex flex-wrap items-center gap-2" role="search">
                    <label class="sr-only" for="mountains-search-q">{{ __('Search') }}</label>
                    <input
                        id="mountains-search-q"
                        name="q"
                        value="{{ $q }}"
                        placeholder="{{ __('Search by name…') }}"
                        class="min-w-[12rem] flex-1 rounded-xl border border-secondary/60 bg-white px-4 py-2 text-sm shadow-sm sm:max-w-xs"
                    >
                    @if(filled($q))
                        <a href="{{ route('admin.mountains.index') }}" class="rounded-xl border border-secondary/60 px-3 py-2 text-sm font-medium text-ink/70 hover:bg-surface">{{ __('Clear') }}</a>
                    @endif
                    <button type="submit" class="rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90">{{ __('Search') }}</button>
                </form>
                <a href="{{ route('admin.mountains.create') }}" class="inline-flex items-center justify-center rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] hover:bg-accent/90">
                    {{ __('Add mountain') }}
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($mountains as $m)
                <article class="group overflow-hidden rounded-xl border border-secondary/50 bg-white shadow-soft transition hover:border-primary/25 hover:shadow-md">
                    <div class="relative aspect-[4/3] overflow-hidden bg-neutral-100">
                        @if($m->image)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($m->image) }}"
                                alt=""
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                                loading="lazy"
                                width="400"
                                height="300"
                            >
                        @else
                            <div class="flex h-full min-h-[8rem] items-center justify-center bg-gradient-to-br from-secondary/30 to-primary/[0.08]">
                                <svg class="h-12 w-12 text-primary/25" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l2.25 3 3.75-4.5L21.75 18M4.5 5.25h15M3.75 21h16.5" />
                                </svg>
                            </div>
                        @endif
                        <span class="absolute left-2 top-2 rounded-md px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-wide shadow-sm {{ $m->is_active ? 'bg-primary text-white' : 'bg-ink/70 text-white' }}">
                            {{ $m->is_active ? __('Visible') : __('Hidden') }}
                        </span>
                    </div>
                    <div class="border-t border-secondary/40 p-3">
                        <p class="truncate font-semibold text-ink">{{ $m->name }}</p>
                        <p class="mt-0.5 font-mono text-xs text-ink/45">#{{ $m->id }} · {{ $m->slug }}</p>
                        @if($m->elevation_m)
                            <p class="mt-1 text-xs font-medium text-accent">{{ number_format($m->elevation_m) }} m</p>
                        @endif
                        <form method="POST" action="{{ route('admin.mountains.toggle', $m) }}" class="mt-3" title="{{ __('Toggle visibility on public site') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full rounded-lg border border-secondary/60 bg-surface/50 py-2 text-xs font-semibold text-primary transition hover:border-primary/35 hover:bg-primary/5">
                                {{ $m->is_active ? __('Turn off') : __('Turn on') }}
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-wrap gap-2 border-t border-secondary/30 bg-surface/30 px-3 py-2">
                        <a href="{{ route('admin.mountains.edit', $m) }}" class="text-xs font-semibold text-primary hover:underline">{{ __('Edit') }}</a>
                        <span class="text-ink/25" aria-hidden="true">|</span>
                        <form action="{{ route('admin.mountains.destroy', $m) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:underline">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-secondary/70 bg-white px-8 py-16 text-center shadow-inner">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l2.25 3 3.75-4.5L21.75 18M4.5 5.25h15M3.75 21h16.5" />
                        </svg>
                    </div>
                    @if(filled($q) && $stats['total'] > 0)
                        <p class="mt-4 font-medium text-ink">{{ __('No mountains match your search') }}</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-ink/65">{{ __('Try another term or clear the search.') }}</p>
                        <a href="{{ route('admin.mountains.index') }}" class="mt-6 inline-flex rounded-xl border border-secondary/60 bg-white px-5 py-2.5 text-sm font-semibold text-ink shadow-sm hover:bg-surface">{{ __('Clear search') }}</a>
                    @else
                        <p class="mt-4 font-medium text-ink">{{ __('No mountains yet') }}</p>
                        <p class="mx-auto mt-2 max-w-md text-sm text-ink/65">{{ __('Add a peak with photo, elevation, and description for the public Mountains page.') }}</p>
                        <a href="{{ route('admin.mountains.create') }}" class="mt-6 inline-flex rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90">{{ __('Add your first mountain') }}</a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($mountains->isNotEmpty() && $mountains->hasPages())
            <div class="flex flex-col gap-3 border-t border-secondary/40 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-ink/60">
                    {{ __('Showing :from–:to of :total', ['from' => $mountains->firstItem(), 'to' => $mountains->lastItem(), 'total' => $mountains->total()]) }}
                </p>
                <div>{{ $mountains->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection
