@extends('layouts.admin')
@section('title', __('Users'))
@section('heading', __('Users'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Users') }}@endsection
@section('content')
<div class="mb-6 flex justify-between"><form method="get"><input name="q" value="{{ $q }}" class="rounded-xl border px-4 py-2 text-sm"></form>
<a href="{{ route('admin.users.create') }}" class="rounded-xl bg-accent px-4 py-2 text-sm font-semibold text-ink">{{ __('Add user') }}</a></div>
<div class="overflow-x-auto rounded-2xl border bg-white shadow-soft"><table class="min-w-full text-sm"><thead class="bg-secondary/30"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">{{ __('Name') }}</th><th class="px-4 py-3">{{ __('Email') }}</th><th class="px-4 py-3 text-right">{{ __('Actions') }}</th></tr></thead>
<tbody class="divide-y">@foreach($users as $u)<tr><td class="px-4 py-3">{{ $u->id }}</td><td class="px-4 py-3">{{ $u->name }}</td><td class="px-4 py-3">{{ $u->email }}</td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.users.edit', $u) }}" class="text-primary">{{ __('Edit') }}</a>
@if($u->id !== auth()->id())<form action="{{ route('admin.users.destroy', $u) }}" method="post" class="inline" onsubmit="return confirm(@json(__('Delete?')));">@csrf @method('DELETE')<button class="text-red-600">{{ __('Delete') }}</button></form>@endif</td></tr>@endforeach</tbody></table></div>
<div class="mt-6">{{ $users->links() }}</div>
@endsection
