@extends('layouts.admin')
@section('title', __('Edit safari'))
@section('heading', __('Edit safari'))
@section('breadcrumb')<a href="{{ route('admin.safari.index') }}">{{ __('Safari') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.safari.update', $safariExperience) }}" method="post" enctype="multipart/form-data" class="max-w-3xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Title') }}</label><input name="title" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('title', $safariExperience->title) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Description') }}</label><textarea name="description" rows="4" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('description', $safariExperience->description) }}</textarea></div>
    <div><label class="text-sm font-medium">{{ __('Duration') }}</label><input name="duration" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('duration', $safariExperience->duration) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Sort order') }}</label><input type="number" name="sort_order" value="{{ old('sort_order', $safariExperience->sort_order) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $safariExperience->is_active)) class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Image') }}</label><input type="file" name="image" accept="image/*">@if($safariExperience->image)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($safariExperience->image) }}" class="mt-2 h-20 rounded">@endif</div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
