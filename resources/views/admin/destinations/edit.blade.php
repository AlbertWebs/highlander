@extends('layouts.admin')

@section('title', __('Edit destination'))
@section('heading', __('Edit destination'))
@section('breadcrumb')
    <a href="{{ route('admin.destinations.index') }}">{{ __('Destinations') }}</a>
    /
    <span class="text-ink/70">{{ $destination->name }}</span>
    /
    {{ __('Edit') }}
@endsection

@section('content')
    @if($errors->any())
        <div
            class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 shadow-sm"
            role="alert"
        >
            <p class="font-semibold">{{ __('Please fix the following:') }}</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.destinations.update', $destination) }}"
        method="post"
        enctype="multipart/form-data"
        class="max-w-4xl space-y-8 rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8"
        onsubmit="if (window.tinymce) tinymce.triggerSave();"
    >
        @csrf
        @method('PUT')

        @include('admin.destinations._form', ['destination' => $destination])

        <div class="flex flex-wrap gap-3 border-t border-secondary/30 pt-6">
            <button type="submit" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                {{ __('Update destination') }}
            </button>
            @if($destination->is_active)
                <a
                    href="{{ route('explore-africa.show', $destination) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-[2.75rem] items-center justify-center gap-1.5 rounded-xl bg-accent px-6 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] transition hover:bg-accent/90"
                >
                    {{ __('View on site') }}
                    <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
            @endif
            <a href="{{ route('admin.destinations.index') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-secondary/50 px-6 py-2.5 text-sm font-medium text-ink transition hover:bg-secondary/20">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
@endsection

@include('admin.destinations._tinymce-description')
