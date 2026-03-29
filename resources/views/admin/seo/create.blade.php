@extends('layouts.admin')
@section('title', __('New SEO'))
@section('heading', __('New SEO entry'))
@section('breadcrumb')<a href="{{ route('admin.seo.index') }}">{{ __('SEO') }}</a> / {{ __('Create') }}@endsection
@section('content')
<form action="{{ route('admin.seo.store') }}" method="post" enctype="multipart/form-data" class="max-w-2xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf
    <div><label class="text-sm font-medium">{{ __('Page key') }}</label><input name="page_key" required placeholder="about" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('page_key') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Meta title') }}</label><input name="meta_title" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('meta_title') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Meta description') }}</label><textarea name="meta_description" rows="3" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('meta_description') }}</textarea></div>
    <div>
        <label class="text-sm font-medium">{{ __('Meta keywords') }}</label>
        <textarea name="meta_keywords" rows="3" placeholder="brand name, luxury safari, …" class="mt-1 w-full rounded-xl border px-4 py-3 text-sm">{{ old('meta_keywords') }}</textarea>
        <p class="mt-1 text-xs text-ink/50">{{ __('Comma-separated; optional for search engines.') }}</p>
    </div>
    <div><label class="text-sm font-medium">{{ __('OG image') }}</label><input type="file" name="og_image" accept="image/*" class="mt-1"></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Save') }}</button>
</form>
@endsection
