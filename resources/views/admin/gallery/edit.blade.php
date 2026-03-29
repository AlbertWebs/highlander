@extends('layouts.admin')
@section('title', __('Edit image'))
@section('heading', __('Edit image'))
@section('breadcrumb')<a href="{{ route('admin.gallery.index') }}">{{ __('Gallery') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.gallery.update', $galleryItem) }}" method="post" enctype="multipart/form-data" class="max-w-xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <img src="{{ $galleryItem->url }}" alt="" class="h-40 rounded-xl object-cover">
    <div><label class="text-sm font-medium">{{ __('Replace image') }}</label><input type="file" name="image" accept="image/*" class="mt-1"></div>
    <div>
        <label class="text-sm font-medium">{{ __('Category') }}</label>
        <select name="gallery_category_id" class="mt-1 w-full rounded-xl border px-4 py-3">
            <option value="">{{ __('Uncategorized') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('gallery_category_id', $galleryItem->gallery_category_id) == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="text-sm font-medium">{{ __('Title') }}</label><input name="title" value="{{ old('title', $galleryItem->title) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Alt text') }}</label><input name="alt" value="{{ old('alt', $galleryItem->alt) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Sort order') }}</label><input type="number" name="sort_order" value="{{ old('sort_order', $galleryItem->sort_order) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $galleryItem->is_active)) class="rounded text-primary">{{ __('Active') }}</label>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
