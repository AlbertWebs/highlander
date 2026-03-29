@extends('layouts.admin')
@section('title', __('New testimonial'))
@section('heading', __('New testimonial'))
@section('breadcrumb')<a href="{{ route('admin.testimonials.index') }}">{{ __('Testimonials') }}</a> / {{ __('Create') }}@endsection
@section('content')
<form action="{{ route('admin.testimonials.store') }}" method="post" enctype="multipart/form-data" class="max-w-2xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Role') }}</label><input name="role" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('role') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Country') }}</label><input name="country" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('country') }}" placeholder="{{ __('e.g. Spain') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Safari type') }}</label><input name="safari_type" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('safari_type') }}" placeholder="{{ __('e.g. Luxury safari') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Quote') }}</label><textarea name="quote" required rows="4" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('quote') }}</textarea></div>
    <div><label class="text-sm font-medium">{{ __('Rating') }}</label><input type="number" name="rating" min="1" max="5" value="{{ old('rating', 5) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Sort order') }}</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" class="rounded text-primary">{{ __('Featured') }}</label>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="show_on_about" value="1" class="rounded text-primary">{{ __('Show on About page') }}</label>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" checked class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Image') }}</label><input type="file" name="image" accept="image/*"></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Save') }}</button>
</form>
@endsection
