@extends('layouts.admin')
@section('title', __('Safari Request').' #'.$req->id)
@section('heading', __('Safari Request').' #'.$req->id)
@section('breadcrumb')<a href="{{ route('admin.safari-requests.index') }}">{{ __('Safari Requests') }}</a> / #{{ $req->id }}@endsection
@section('content')
@if(session('success'))
    <div class="mb-6 rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('success') }}</div>
@endif

<div class="grid gap-8 lg:grid-cols-[1fr_minmax(0,22rem)]">
    <div class="space-y-6 rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <h2 class="text-lg font-semibold text-primary">{{ __('Request details') }}</h2>
        <dl class="grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="text-ink/60">{{ __('Full name') }}</dt><dd class="font-medium">{{ $req->full_name }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Email') }}</dt><dd><a href="mailto:{{ $req->email }}" class="text-primary hover:underline">{{ $req->email }}</a></dd></div>
            <div><dt class="text-ink/60">{{ __('Phone') }}</dt><dd>{{ $req->phone }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Country') }}</dt><dd>{{ $req->country ?? '—' }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Preferred contact') }}</dt><dd>{{ $req->contactMethodLabel() }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Arrival') }}</dt><dd>{{ $req->arrival_date->format('M j, Y') }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Departure') }}</dt><dd>{{ $req->departure_date->format('M j, Y') }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Trip duration') }}</dt><dd>{{ $req->trip_duration !== null ? $req->trip_duration.' '.__('days') : '—' }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Flexible dates') }}</dt><dd>{{ $req->flexible_dates ? __('Yes') : __('No') }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Adults / Children') }}</dt><dd>{{ $req->adults }} / {{ $req->children }}</dd></div>
            @if($req->children > 0 && $req->children_ages)
                <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Children ages') }}</dt><dd>{{ $req->children_ages }}</dd></div>
            @endif
            <div><dt class="text-ink/60">{{ __('Group type') }}</dt><dd>{{ $req->groupTypeLabel() }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Budget') }}</dt><dd>{{ $req->budgetLabel() }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Destinations') }}</dt><dd>{{ is_array($req->destinations) && count($req->destinations) ? implode(', ', $req->destinations) : '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Experience types') }}</dt><dd>{{ is_array($req->experience_types) && count($req->experience_types) ? implode(', ', $req->experience_types) : '—' }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Accommodation') }}</dt><dd>{{ $req->accommodationLabel() }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Room type') }}</dt><dd>{{ $req->roomTypeLabel() }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Transport') }}</dt><dd>{{ $req->transportLabel() }}</dd></div>
            <div><dt class="text-ink/60">{{ __('Airport pickup') }}</dt><dd>{{ $req->airport_pickup ? __('Yes') : __('No') }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Activities') }}</dt><dd>{{ is_array($req->activities) && count($req->activities) ? implode(', ', $req->activities) : '—' }}</dd></div>
            @if($req->special_requests)
                <div class="sm:col-span-2"><dt class="text-ink/60">{{ __('Special requests') }}</dt><dd class="whitespace-pre-wrap">{{ $req->special_requests }}</dd></div>
            @endif
        </dl>
        <p class="text-xs text-ink/50">{{ __('Submitted') }} {{ $req->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="h-fit rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft">
        <h2 class="text-lg font-semibold text-primary">{{ __('Manage') }}</h2>
        <form method="post" action="{{ route('admin.safari-requests.update', $req) }}" class="mt-4 space-y-4">
            @csrf
            @method('PATCH')
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-900">{{ $errors->first() }}</div>
            @endif
            <div>
                <label class="text-sm font-medium text-ink">{{ __('Status') }}</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-secondary/60 bg-white px-3 py-2 text-sm">
                    @foreach($statusOptions as $val => $lab)
                        <option value="{{ $val }}" @selected($req->status === $val)>{{ $lab }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-ink">{{ __('Admin notes') }}</label>
                <textarea name="admin_notes" rows="6" class="mt-1 w-full rounded-xl border border-secondary/60 bg-white px-3 py-2 text-sm" placeholder="{{ __('Internal notes…') }}">{{ old('admin_notes', $req->admin_notes) }}</textarea>
            </div>
            <button type="submit" class="w-full rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90">{{ __('Save changes') }}</button>
        </form>
        <form method="post" action="{{ route('admin.safari-requests.destroy', $req) }}" class="mt-4" onsubmit="return confirm(@json(__('Delete this request permanently?')));">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-800 hover:bg-red-100">{{ __('Delete request') }}</button>
        </form>
        <a href="{{ route('admin.safari-requests.index') }}" class="mt-4 inline-block text-sm text-primary hover:underline">{{ __('← Back to list') }}</a>
    </div>
</div>
@endsection
