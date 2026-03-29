@if(! empty($vimeoId))
    <iframe
        class="pointer-events-none absolute left-1/2 top-1/2 h-[56.25vw] min-h-full w-[177.78vh] min-w-full max-w-none -translate-x-1/2 -translate-y-1/2 border-0"
        src="https://player.vimeo.com/video/{{ $vimeoId }}?badge=0&amp;autopause=0&amp;autoplay=1&amp;background=1&amp;byline=0&amp;loop=1&amp;muted=1&amp;playsinline=1&amp;portrait=0&amp;title=0&amp;transparent=0"
        allow="autoplay; fullscreen; picture-in-picture"
        allowfullscreen
        title=""
    ></iframe>
@else
    <img
        src="{{ $imageUrl }}"
        alt=""
        class="absolute inset-0 h-full w-full object-cover img-zoom-hover"
        loading="lazy"
        width="900"
        height="1200"
    >
@endif
