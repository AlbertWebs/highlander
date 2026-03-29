@extends('layouts.admin')
@section('title', __('Edit booking'))
@section('heading', __('Edit booking'))
@section('breadcrumb')<a href="{{ route('admin.bookings.index') }}">{{ __('Bookings') }}</a> / {{ __('Edit') }}@endsection
@section('content')
<form action="{{ route('admin.bookings.update', $booking) }}" method="post" class="max-w-2xl space-y-6 rounded-2xl border bg-white p-8 shadow-soft">@csrf @method('PUT')
    <div><label class="text-sm font-medium">{{ __('Status') }}</label>
        <select name="status" class="mt-1 w-full rounded-xl border px-4 py-3">
            @foreach(['pending','confirmed','cancelled','completed'] as $s)
                <option value="{{ $s }}" @selected(old('status', $booking->status) === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="text-sm font-medium">{{ __('Tour') }}</label>
        <select name="tour_id" class="mt-1 w-full rounded-xl border px-4 py-3">
            <option value="">—</option>
            @foreach($tours as $t)
                <option value="{{ $t->id }}" @selected(old('tour_id', $booking->tour_id) == $t->id)>{{ $t->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div><label class="text-sm font-medium">{{ __('Start') }}</label><input type="date" name="start_date" value="{{ old('start_date', $booking->start_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
        <div><label class="text-sm font-medium">{{ __('End') }}</label><input type="date" name="end_date" value="{{ old('end_date', $booking->end_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    </div>
    <div><label class="text-sm font-medium">{{ __('Guests') }}</label><input type="number" name="guests" min="1" value="{{ old('guests', $booking->guests) }}" class="mt-1 w-full rounded-xl border px-4 py-3"></div>
    <div><label class="text-sm font-medium">{{ __('Message') }}</label><textarea name="message" rows="5" class="mt-1 w-full rounded-xl border px-4 py-3">{{ old('message', $booking->message) }}</textarea></div>
    <button class="rounded-xl bg-primary px-6 py-3 text-white">{{ __('Update') }}</button>
</form>
@endsection
