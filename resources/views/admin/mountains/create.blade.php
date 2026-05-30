@extends('layouts.admin')
@section('title', __('New mountain')) @section('heading', __('New mountain'))
@section('breadcrumb')<a href="{{ route('admin.mountains.index') }}">{{ __('Mountains') }}</a> / {{ __('Create') }}@endsection
@section('content')
<form action="{{ route('admin.mountains.store') }}" method="post" enctype="multipart/form-data" class="max-w-3xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft" onsubmit="if (window.tinymce) tinymce.triggerSave();">@csrf
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name') }}"></div>
    <div>
        <label class="text-sm font-medium" for="mountain-description">{{ __('Description') }}</label>
        <p class="mt-1 text-xs text-ink/50">{{ __('Rich text editor — bold, lists, links.') }}</p>
        <textarea id="mountain-description" name="description" rows="10" class="mt-1 w-full rounded-xl border px-4 py-3 font-mono text-sm">{{ old('description') }}</textarea>
    </div>
    <div><label class="text-sm font-medium">{{ __('Elevation (m)') }}</label><input type="number" name="elevation_m" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('elevation_m') }}"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Image') }}</label><input type="file" name="image" accept="image/*"></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Save') }}</button>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#mountain-description',
            height: 380,
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | styles | bold italic | bullist numlist | link | code',
            entity_encoding: 'raw',
        });
    }
});
</script>
@endpush
