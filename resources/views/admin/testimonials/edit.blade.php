@extends('layouts.admin')
@section('title', __('Edit testimonial'))
@section('heading', __('Edit testimonial'))
@section('breadcrumb')<a href="{{ route('admin.testimonials.index') }}">{{ __('Testimonials') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.testimonials.update', $testimonial) }}" method="post" enctype="multipart/form-data" class="max-w-2xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name', $testimonial->name) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Role') }}</label><input name="role" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('role', $testimonial->role) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Country') }}</label><input name="country" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('country', $testimonial->country) }}" placeholder="{{ __('e.g. Spain') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Safari type') }}</label><input name="safari_type" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('safari_type', $testimonial->safari_type) }}" placeholder="{{ __('e.g. Luxury safari') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Quote') }}</label><textarea name="quote" required rows="4" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('quote', $testimonial->quote) }}</textarea></div>
    <div><label class="text-sm font-medium">{{ __('Rating') }}</label><input type="number" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Sort order') }}</label><input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $testimonial->is_featured)) class="rounded text-primary">{{ __('Featured') }}</label>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="show_on_about" value="1" @checked(old('show_on_about', $testimonial->show_on_about)) class="rounded text-primary">{{ __('Show on About page') }}</label>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active)) class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Image') }}</label><input type="file" name="image" accept="image/*">@if($testimonial->image)<img src="{{ $testimonial->imageUrl() }}" class="mt-2 h-16 rounded-full object-cover">@endif</div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
