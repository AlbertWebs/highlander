@extends('layouts.admin')
@section('title', __('New user'))
@section('heading', __('New user'))
@section('breadcrumb')<a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a> / {{ __('Create') }}@endsection
@section('content')
<form action="{{ route('admin.users.store') }}" method="post" class="max-w-md space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf
    <div><label class="text-sm font-medium">{{ __('Name') }}</label><input name="name" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('name') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Email') }}</label><input type="email" name="email" required class="mt-1 w-full rounded-xl border px-4 py-3" value="{{ old('email') }}"></div>
    <div><label class="text-sm font-medium">{{ __('Password') }}</label><input type="password" name="password" required class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Confirm') }}</label><input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <p class="text-xs text-ink/60">{{ __('New users are created as administrators.') }}</p>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Save') }}</button>
</form>
@endsection
