@php
    $faviconPath = \App\Models\SiteSetting::getValue('site_favicon', '');
    $faviconUrl = \App\Models\SiteSetting::publicUrl($faviconPath);
@endphp
@if($faviconUrl)
    @php
        $ext = strtolower(pathinfo((string) $faviconPath, PATHINFO_EXTENSION));
        $type = match ($ext) {
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'gif' => 'image/gif',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'image/png',
        };
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" type="{{ $type }}">
@endif
