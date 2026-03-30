@php
    $vimeoId = $tour->featuredCardVimeoId();
    $showVideo = $tour->featuredCardShowsVideo();
@endphp
@if($showVideo && $vimeoId)
    <iframe
        class="pointer-events-none absolute left-1/2 top-1/2 h-[56.25vw] min-h-full w-[177.78vh] min-w-full max-w-none -translate-x-1/2 -translate-y-1/2 border-0"
        src="https://player.vimeo.com/video/{{ $vimeoId }}?badge=0&amp;autopause=0&amp;autoplay=1&amp;background=1&amp;byline=0&amp;loop=1&amp;muted=1&amp;playsinline=1&amp;portrait=0&amp;title=0&amp;transparent=0"
        allow="autoplay; fullscreen; picture-in-picture"
        allowfullscreen
        title="{{ $tour->title }}"
    ></iframe>
@elseif($showVideo)
    <video
        class="absolute inset-0 h-full w-full object-cover img-zoom-hover"
        autoplay
        muted
        loop
        playsinline
        preload="metadata"
    >
        <source src="{{ $tour->featured_video_url }}">
    </video>
@elseif($tour->imageUrl())
    <img src="{{ $tour->imageUrl() }}" alt="" class="h-full w-full object-cover img-zoom-hover" loading="lazy">
@else
    <div class="flex h-full items-center justify-center bg-gradient-to-br from-primary/25 to-accent/35 text-ink/40">{{ __('Media') }}</div>
@endif
