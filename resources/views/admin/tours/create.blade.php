@extends('layouts.admin')

@section('title', __('New tour'))
@section('heading', __('New tour'))
@section('breadcrumb')
    <a href="{{ route('admin.tours.index') }}">{{ __('Tours') }}</a> / {{ __('Create') }}
@endsection

@section('content')
<form action="{{ route('admin.tours.store') }}" method="post" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-2xl border border-secondary/50 bg-white p-8 shadow-soft">
    @csrf
    @include('admin.tours._form', ['tour' => new \App\Models\Tour()])
    <div class="flex gap-3">
        <button type="submit" class="rounded-xl bg-primary px-6 py-3 font-semibold text-white">{{ __('Save') }}</button>
        <a href="{{ route('admin.tours.index') }}" class="rounded-xl border border-secondary/50 px-6 py-3">{{ __('Cancel') }}</a>
    </div>
</form>
@endsection
