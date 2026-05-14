@extends('layouts.admin')

@section('title', __('Edit article'))
@section('heading', __('Edit article'))
@section('breadcrumb')
    <a href="{{ route('admin.articles.index') }}">{{ __('Articles') }}</a> / {{ __('Edit') }}
@endsection

@section('content')
<form
    action="{{ route('admin.articles.update', $article) }}"
    method="post"
    enctype="multipart/form-data"
    class="max-w-4xl space-y-8 rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft md:p-10"
    onsubmit="if (window.tinymce) tinymce.triggerSave();"
>
    @csrf
    @method('PUT')
    @include('admin.articles._form', ['article' => $article])
    <div class="flex flex-wrap gap-3 border-t border-secondary/30 pt-8">
        <button type="submit" class="rounded-xl bg-primary px-6 py-3 font-semibold text-white shadow-sm ring-1 ring-black/[0.04] hover:bg-primary/90">{{ __('Update article') }}</button>
        <a href="{{ route('admin.articles.index') }}" class="rounded-xl border border-secondary/50 bg-surface px-6 py-3 font-medium text-ink hover:bg-secondary/30">{{ __('Cancel') }}</a>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#article-body',
            height: 440,
            menubar: false,
            plugins: 'link lists',
            toolbar: 'undo redo | styles | bold italic | bullist numlist | link',
            branding: false,
            promotion: false,
        });
    }
});
</script>
@endpush
