@extends('layouts.admin')
@section('title', __('Edit user'))
@section('heading', __('Edit user'))
@section('breadcrumb')<a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.users.update', $user) }}" method="post" class="max-w-md space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name', $user->name) }}"></div>
    <div><label class="text-sm font-medium">{{ __('Email') }}</label><input type="email" name="email" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('email', $user->email) }}"></div>
    <div><label class="text-sm font-medium">{{ __('New password') }}</label><input type="password" name="password" class="mt-1 w-full rounded-xl border px-4 py-3" placeholder="{{ __('Leave blank to keep') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Confirm') }}</label><input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
