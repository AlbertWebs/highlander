@extends('layouts.admin')
@section('title', __('Edit SEO'))
@section('heading', __('Edit SEO'))
@section('breadcrumb')<a href="{{ route('admin.seo.index') }}">{{ __('SEO') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.seo.update', $seoMeta) }}" method="post" enctype="multipart/form-data" class="max-w-2xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Page key') }}</label><input name="page_key" required class="mt-1 w-full rounded-xl border px-4 py-3 font-mono text-sm" value="{{ old('page_key', $seoMeta->page_key) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Meta title') }}</label><input name="meta_title" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('meta_title', $seoMeta->meta_title) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Meta description') }}</label><textarea name="meta_description" rows="3" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('meta_description', $seoMeta->meta_description) }}</textarea></div>
    <div>
        <label class="text-sm font-medium">{{ __('Meta keywords') }}</label>
        <textarea name="meta_keywords" rows="3" placeholder="brand name, luxury safari Kenya, …" class="mt-1 w-full rounded-xl border px-4 py-3 text-sm">{{ old('meta_keywords', $seoMeta->meta_keywords) }}</textarea>
        <p class="mt-1 text-xs text-ink/50">{{ __('Comma-separated phrases; keep relevant and avoid stuffing.') }}</p>
    </div>
    <div><label class="text-sm font-medium">{{ __('OG image') }}</label><input type="file" name="og_image" accept="image/*" class="mt-1">@if($seoMeta->og_image)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($seoMeta->og_image) }}" class="mt-2 h-20 rounded">@endif</div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
