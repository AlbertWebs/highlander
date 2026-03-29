@extends('layouts.admin')

@section('title', __('New destination'))
@section('heading', __('New destination'))
@section('breadcrumb')
    <a href="{{ route('admin.destinations.index') }}">{{ __('Destinations') }}</a>
    /
    {{ __('Create') }}
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
        action="{{ route('admin.destinations.store') }}"
        method="post"
        enctype="multipart/form-data"
        class="max-w-4xl space-y-8 rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8"
    >
        @csrf

        @include('admin.destinations._form')

        <div class="flex flex-wrap gap-3 border-t border-secondary/30 pt-6">
            <button type="submit" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                {{ __('Create destination') }}
            </button>
            <a href="{{ route('admin.destinations.index') }}" class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-secondary/50 px-6 py-2.5 text-sm font-medium text-ink transition hover:bg-secondary/20">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
@endsection
