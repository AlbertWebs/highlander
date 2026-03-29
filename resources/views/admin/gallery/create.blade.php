@extends('layouts.admin')
@section('title', __('New image'))
@section('heading', __('New image'))
@section('breadcrumb')<a href="{{ route('admin.gallery.index') }}">{{ __('Gallery') }}</a> / {{ __('Upload') }}@endsection
@section('content')
<form action="{{ route('admin.gallery.store') }}" method="post" enctype="multipart/form-data" class="max-w-xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft" x-data="{ drag: false }">@csrf
    <div class="rounded-xl border-2 border-dashed border-secondary p-8 text-center" @dragover.prevent="drag=true" @dragleave="drag=false" @drop.prevent="drag=false; $refs.file.files = $event.dataTransfer.files">
        <input type="file" name="image" x-ref="file" required accept="image/*" class="w-full">
        <p class="mt-2 text-xs text-ink/60">{{ __('Or drag and drop an image here') }}</p>
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('Category') }}</label>
        <select name="gallery_category_id" class="mt-1 w-full rounded-xl border px-4 py-3">
            <option value="">{{ __('Uncategorized') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="text-sm font-medium">{{ __('Title') }}</label><input name="title" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Alt text') }}</label><input name="alt" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Sort order') }}</label><input type="number" name="sort_order" value="0" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded text-primary">{{ __('Active') }}</label>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Upload') }}</button>
</form>
@endsection
