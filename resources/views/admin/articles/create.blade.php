@extends('layouts.admin')
@section('title', __('New article'))
@section('heading', __('New article'))
@section('breadcrumb')<a href="{{ route('admin.articles.index') }}">{{ __('Articles') }}</a> / {{ __('Create') }}@endsection
@section('content')
<form action="{{ route('admin.articles.store') }}" method="post" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft" onsubmit="if (window.tinymce) tinymce.triggerSave();">@csrf
    <div><label class="text-sm font-medium">{{ __('Title') }}</label><input name="title" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('title') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Excerpt') }}</label><textarea name="excerpt" rows="2" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('excerpt') }}</textarea></div>
    <div><label class="text-sm font-medium">{{ __('Body') }}</label><textarea name="body" id="article-body" rows="12" class="mt-1 w-full rounded-xl border px-4 py-3 font-mono text-sm">{{ old('body') }}</textarea></div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div><label class="text-sm font-medium">{{ __('Published at') }}</label><input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
        <div><label class="text-sm font-medium">{{ __('Featured image') }}</label><input type="file" name="featured_image" accept="image/*" class="mt-1"></div>
    </div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Meta title') }}</label><input name="meta_title" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('meta_title') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Meta description') }}</label><input name="meta_description" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('meta_description') }}"></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Save') }}</button>
</form>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({ selector: '#article-body', height: 420, menubar: false, plugins: 'link lists', toolbar: 'undo redo | styles | bold italic | bullist numlist | link' });
    }
});
</script>
@endpush
