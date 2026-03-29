@extends('layouts.admin')
@section('title', __('Message').' #'.$contactMessage->id)
@section('heading', __('Contact message'))
@section('breadcrumb')<a href="{{ route('admin.contact-messages.index') }}">{{ __('Messages') }}</a> / #{{ $contactMessage->id }}@endsection
@section('content')
<div class="max-w-2xl rounded-2xl border bg-white p-8 shadow-soft space-y-4">
    <p><strong>{{ __('From') }}:</strong> {{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</p>
    <p><strong>{{ __('Subject') }}:</strong> {{ $contactMessage->subject ?? '—' }}</p>
    <p class="whitespace-pre-wrap"><strong>{{ __('Message') }}:</strong><br>{{ $contactMessage->message }}</p>
    <form method="POST" action="{{ route('admin.contact-messages.read', $contactMessage) }}">@csrf @method('PATCH')<button class="rounded-xl bg-secondary px-4 py-2 text-sm">{{ __('Mark read') }}</button></form>
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-block rounded-xl border px-4 py-2">{{ __('Back') }}</a>
</div>
@endsection
