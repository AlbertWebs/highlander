@props([
    'recentArticles',
])

<aside class="space-y-6 lg:sticky lg:top-28 lg:self-start" data-aos="fade-up" data-aos-duration="700">
    <div class="card-depth rounded-card p-6">
        <h2 class="font-serif text-xl font-semibold text-primary">{{ __('From the journal') }}</h2>
        <p class="mt-2 text-sm leading-relaxed text-ink/70">
            {{ __('Field notes, seasonal tips, and stories from our guides—written to help you plan a smarter safari.') }}
        </p>
    </div>

    @if($recentArticles->isNotEmpty())
        <div class="card-depth rounded-card p-6">
            <h2 class="font-serif text-xl font-semibold text-primary">{{ __('Recent posts') }}</h2>
            <ul class="mt-4 space-y-4">
                @foreach($recentArticles as $a)
                    <li>
                        <a href="{{ route('articles.show', $a) }}" class="group flex gap-3 rounded-lg transition hover:bg-primary/[0.04]">
                            @if($a->featured_image)
                                <span class="img-zoom-parent relative h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-secondary/35">
                                    <img
                                        src="{{ $a->featuredImageUrl() }}"
                                        alt=""
                                        class="img-zoom-hover h-full w-full object-cover"
                                        loading="lazy"
                                        width="56"
                                        height="56"
                                    >
                                </span>
                            @else
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-secondary/30 to-primary/[0.08] font-serif text-xs italic text-primary/30" aria-hidden="true">{{ __('Journal') }}</span>
                            @endif
                            <span class="min-w-0 flex-1">
                                <span class="line-clamp-2 text-sm font-semibold leading-snug text-primary group-hover:underline">{{ $a->title }}</span>
                                <span class="mt-0.5 block text-xs text-ink/55">{{ optional($a->published_at)->format('M j, Y') }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-card border border-primary/25 bg-gradient-to-br from-primary/[0.07] to-tint-green/40 p-6 shadow-sm">
        <h2 class="font-serif text-xl font-semibold text-primary">{{ __('Plan your safari') }}</h2>
        <p class="mt-2 text-sm leading-relaxed text-ink/75">
            {{ __('Tell us your dates and style—we will shape an itinerary around the wildlife and landscapes you care about.') }}
        </p>
        <a href="{{ route('plan-my-safari') }}" class="btn-primary mt-5 w-full sm:w-auto">{{ __('Start planning') }}</a>
    </div>

    <nav class="card-depth rounded-card p-6" aria-label="{{ __('Site sections') }}">
        <h2 class="font-serif text-xl font-semibold text-primary">{{ __('Explore') }}</h2>
        <ul class="mt-4 space-y-2.5 text-sm font-medium">
            <li>
                <a href="{{ route('gallery') }}" class="text-primary transition hover:underline">{{ __('Gallery') }}</a>
            </li>
            <li>
                <a href="{{ route('safari') }}" class="text-primary transition hover:underline">{{ __('Safari experiences') }}</a>
            </li>
            <li>
                <a href="{{ route('explore-africa') }}" class="text-primary transition hover:underline">{{ __('Destinations') }}</a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="text-primary transition hover:underline">{{ __('Contact') }}</a>
            </li>
        </ul>
    </nav>
</aside>
