@extends('layouts.admin')
@section('title', __('Booking').' #'.$booking->id)
@section('heading', __('Booking').' #'.$booking->id)
@section('breadcrumb')<a href="{{ route('admin.bookings.index') }}">{{ __('Bookings') }}</a> / #{{ $booking->id }}@endsection
@section('content')
<div class="max-w-2xl rounded-2xl border bg-white p-8 shadow-soft space-y-4 text-sm">
    <p><strong>{{ __('Name') }}:</strong> {{ $booking->name }}</p>
    <p><strong>{{ __('Email') }}:</strong> {{ $booking->email }}</p>
    <p><strong>{{ __('Phone') }}:</strong> {{ $booking->phone ?? '—' }}</p>
    <p><strong>{{ __('Tour') }}:</strong> {{ $booking->tour?->title ?? '—' }}</p>
    <p><strong>{{ __('Guests') }}:</strong> {{ $booking->guests }}</p>
    <p><strong>{{ __('Dates') }}:</strong> {{ $booking->start_date?->format('Y-m-d') ?? '—' }} — {{ $booking->end_date?->format('Y-m-d') ?? '—' }}</p>
    <p><strong>{{ __('Status') }}:</strong> {{ $booking->status }}</p>
    <p class="whitespace-pre-wrap"><strong>{{ __('Message') }}:</strong><br>{{ $booking->message }}</p>
    <div class="flex gap-3 pt-4"><a href="{{ route('admin.bookings.edit', $booking) }}" class="rounded-xl bg-primary px-4 py-2 text-white">{{ __('Edit') }}</a>
    <a href="{{ route('admin.bookings.index') }}" class="rounded-xl border px-4 py-2">{{ __('Back') }}</a></div>
</div>
@endsection
