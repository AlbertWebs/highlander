@extends('layouts.admin')
@section('title', __('Edit mountain')) @section('heading', __('Edit mountain'))
@section('breadcrumb')<a href="{{ route('admin.mountains.index') }}">{{ __('Mountains') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.mountains.update', $mountain) }}" method="post" enctype="multipart/form-data" class="max-w-3xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name', $mountain->name) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Description') }}</label><textarea name="description" rows="4" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('description', $mountain->description) }}</textarea></div>
    <div><label class="text-sm font-medium">{{ __('Elevation (m)') }}</label><input type="number" name="elevation_m" class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('elevation_m', $mountain->elevation_m) }}"></div>
    <label class="flex gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $mountain->is_active)) class="rounded text-primary">{{ __('Active') }}</label>
    <div><label class="text-sm font-medium">{{ __('Image') }}</label><input type="file" name="image" accept="image/*">@if($mountain->image)<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image) }}" class="mt-2 h-20 rounded">@endif</div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
