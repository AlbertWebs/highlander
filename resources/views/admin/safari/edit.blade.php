@extends('layouts.admin')

@section('title', __('Edit safari'))
@section('heading', __('Edit safari'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
    /
    <a href="{{ route('admin.safari.index') }}">{{ __('Safari') }}</a>
    /
    <span class="text-ink/70">{{ \Illuminate\Support\Str::limit($safariExperience->title, 40) }}</span>
    /
    {{ __('Edit') }}
@endsection

@section('content')
<div class="mx-auto max-w-6xl space-y-6 pb-28">
    <div class="flex flex-col gap-4 border-b border-secondary/40 pb-6 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-primary">{{ __('Safari experience') }} #{{ $safariExperience->id }}</p>
            <h2 class="mt-1 text-xl font-semibold text-ink sm:text-2xl">{{ $safariExperience->title }}</h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/65">
                {{ __('Update the card on the public Safari page. Save often while editing.') }}
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            <a
                href="{{ route('admin.safari.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-secondary/60 bg-white px-4 py-2.5 text-sm font-medium text-ink shadow-sm transition hover:bg-surface"
            >
                {{ __('← Back to list') }}
            </a>
            @if($safariExperience->is_active)
                <a
                    href="{{ route('safari.show', $safariExperience) }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] transition hover:bg-accent/90"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    {{ __('View on site') }}
                    <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
            @endif
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 shadow-sm" role="alert">
            <p class="font-semibold">{{ __('Please fix the following:') }}</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-8 xl:grid-cols-3 xl:items-start">
        <div class="min-w-0 xl:col-span-2">
        <form
            action="{{ route('admin.safari.update', $safariExperience) }}"
            method="post"
            enctype="multipart/form-data"
            class="space-y-0"
        >
            @csrf
            @method('PUT')

            {{-- Sticky actions: visible without scrolling long forms --}}
            <div class="sticky top-0 z-20 -mx-1 mb-6 flex flex-wrap items-center gap-2 rounded-xl border border-secondary/50 bg-white/95 px-3 py-3 shadow-sm ring-1 ring-black/[0.03] backdrop-blur-sm sm:gap-3 sm:px-4">
                <button
                    type="submit"
                    class="inline-flex min-h-[2.5rem] flex-1 items-center justify-center rounded-xl bg-primary px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 sm:flex-none"
                >
                    {{ __('Save changes') }}
                </button>
                @if($safariExperience->is_active)
                    <a
                        href="{{ route('safari.show', $safariExperience) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-[2.5rem] flex-1 items-center justify-center gap-1.5 rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] transition hover:bg-accent/90 sm:flex-none"
                    >
                        {{ __('View on site') }}
                        <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </a>
                @endif
                <a href="{{ route('admin.safari.index') }}" class="inline-flex min-h-[2.5rem] flex-1 items-center justify-center rounded-xl border border-secondary/60 bg-white px-4 py-2 text-sm font-medium text-ink transition hover:bg-surface sm:flex-none">
                    {{ __('Cancel') }}
                </a>
            </div>

            @include('admin.safari._form', ['safariExperience' => $safariExperience])

            <div class="mt-8 flex flex-wrap gap-3 border-t border-secondary/30 pt-6">
                <button
                    type="submit"
                    class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                >
                    {{ __('Save changes') }}
                </button>
                @if($safariExperience->is_active)
                    <a
                        href="{{ route('safari.show', $safariExperience) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-[2.75rem] items-center justify-center gap-1.5 rounded-xl bg-accent px-6 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] transition hover:bg-accent/90"
                    >
                        {{ __('View on site') }}
                        <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </a>
                @endif
                <a href="{{ route('admin.safari.index') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-secondary/50 px-6 py-2.5 text-sm font-medium text-ink transition hover:bg-secondary/20">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
        </div>

        <aside class="min-w-0 space-y-4 xl:sticky xl:top-20">
            <div class="rounded-2xl border border-secondary/50 bg-gradient-to-br from-surface to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary">{{ __('Tips') }}</p>
                <ul class="mt-3 list-inside list-disc space-y-2 text-sm leading-relaxed text-ink/70">
                    <li>{{ __('Use sort order so the most important styles appear first.') }}</li>
                    <li>{{ __('Turn off visibility to hide a card without deleting it.') }}</li>
                    <li>{{ __('After saving, the public page updates immediately.') }}</li>
                </ul>
            </div>
            <div class="rounded-2xl border border-red-200/80 bg-red-50/50 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-red-800/90">{{ __('Danger zone') }}</p>
                <p class="mt-2 text-sm leading-relaxed text-ink/80">
                    {{ __('Deleting removes this safari style from the admin list and the public site. This cannot be undone.') }}
                </p>
                <form action="{{ route('admin.safari.destroy', $safariExperience) }}" method="post" class="mt-4" onsubmit="return confirm(@json(__('Delete this safari experience? This cannot be undone.')));">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-xl border border-red-300 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-50">
                        {{ __('Delete this experience') }}
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection

