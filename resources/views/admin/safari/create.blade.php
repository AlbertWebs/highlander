@extends('layouts.admin')

@section('title', __('New safari'))
@section('heading', __('New safari'))
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
    /
    <a href="{{ route('admin.safari.index') }}">{{ __('Safari') }}</a>
    /
    {{ __('Create') }}
@endsection

@section('content')
<div class="mx-auto max-w-5xl space-y-8 pb-24">
    <div class="border-b border-secondary/40 pb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-primary">{{ __('New block') }}</p>
        <h2 class="mt-1 text-xl font-semibold text-ink sm:text-2xl">{{ __('Add a safari experience') }}</h2>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/65">
            {{ __('Create a card for the public Safari page. You can add an image and fine-tune order after saving.') }}
        </p>
        <a
            href="{{ route('admin.safari.index') }}"
            class="mt-4 inline-flex items-center justify-center rounded-xl border border-secondary/60 bg-white px-4 py-2.5 text-sm font-medium text-ink shadow-sm transition hover:bg-surface"
        >
            {{ __('← Back to list') }}
        </a>
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

    <form
        action="{{ route('admin.safari.store') }}"
        method="post"
        enctype="multipart/form-data"
        class="space-y-0"
    >
        @csrf

        @include('admin.safari._form')

        <div class="mt-8 flex flex-wrap gap-3 border-t border-secondary/30 pt-6">
            <button
                type="submit"
                class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
            >
                {{ __('Create experience') }}
            </button>
            <a href="{{ route('admin.safari.index') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-secondary/50 px-6 py-2.5 text-sm font-medium text-ink transition hover:bg-secondary/20">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
