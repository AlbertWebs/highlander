@extends('layouts.admin')

@section('title', __('Edit tour'))
@section('heading', __('Edit tour'))
@section('breadcrumb')
    <a href="{{ route('admin.tours.index') }}">{{ __('Tours') }}</a> / {{ __('Edit') }}
@endsection

@section('content')
<div class="mb-6 max-w-4xl rounded-2xl border border-primary/25 bg-primary/5 px-5 py-4 text-sm text-ink/90">
    <a href="{{ route('admin.tours.itinerary.edit', $tour) }}" class="font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Manage day-by-day itinerary') }}</a>
    <span class="text-ink/60"> — {{ __('separate from this overview form.') }}</span>
</div>
<form action="{{ route('admin.tours.update', $tour) }}" method="post" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-2xl border border-secondary/50 bg-white p-8 shadow-soft">
    @csrf
    @method('PUT')
    @include('admin.tours._form', ['tour' => $tour])
    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-primary px-6 py-3 font-semibold text-white">{{ __('Update') }}</button>
        <a href="{{ route('admin.tours.index') }}" class="rounded-xl border border-secondary/50 px-6 py-3">{{ __('Cancel') }}</a>
    </div>
</form>
@endsection
