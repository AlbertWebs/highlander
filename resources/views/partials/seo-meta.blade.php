@php
    $ogUrl = null;
    if (! empty($og_image ?? null)) {
        $ogUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($og_image);
    }
@endphp
@if(filled($meta_description ?? null))
    <meta name="description" content="{{ $meta_description }}">
@endif
@if(filled($meta_keywords ?? null))
    <meta name="keywords" content="{{ $meta_keywords }}">
@endif
@if(filled($meta_title ?? null))
    <meta property="og:title" content="{{ $meta_title }}">
@endif
@if(filled($meta_description ?? null))
    <meta property="og:description" content="{{ $meta_description }}">
@endif
@if($ogUrl)
    <meta property="og:image" content="{{ $ogUrl }}">
    <meta name="twitter:card" content="summary_large_image">
@else
    <meta name="twitter:card" content="summary">
@endif
