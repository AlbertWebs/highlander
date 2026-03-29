<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New Safari Request') }}</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #1f2937;">
    <h1 style="font-size: 1.25rem;">{{ __('New Safari Request') }}</h1>
    <p><strong>{{ __('Name') }}:</strong> {{ $safariRequest->full_name }}</p>
    <p><strong>{{ __('Email') }}:</strong> <a href="mailto:{{ $safariRequest->email }}">{{ $safariRequest->email }}</a></p>
    <p><strong>{{ __('Phone') }}:</strong> {{ $safariRequest->phone }}</p>
    <p><strong>{{ __('Travel dates') }}:</strong>
        {{ $safariRequest->arrival_date->format('M j, Y') }} → {{ $safariRequest->departure_date->format('M j, Y') }}
        @if($safariRequest->trip_duration)
            ({{ $safariRequest->trip_duration }} {{ __('days') }})
        @endif
    </p>
    <p><strong>{{ __('Destinations') }}:</strong>
        @if(is_array($safariRequest->destinations) && count($safariRequest->destinations))
            {{ implode(', ', $safariRequest->destinations) }}
        @else
            —
        @endif
    </p>
    <p><strong>{{ __('Budget') }}:</strong> {{ $safariRequest->budgetLabel() }}</p>
    <p style="margin-top: 1.5rem;">
        <a href="{{ route('admin.safari-requests.show', $safariRequest, true) }}" style="color: #16a34a;">{{ __('Open in admin') }}</a>
    </p>
    <p style="margin-top: 2rem; font-size: 0.875rem; color: #6b7280;">{{ config('app.name') }}</p>
</body>
</html>
