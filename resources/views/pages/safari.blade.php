@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Safari').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', [
    'title' => __('Safari'),
    'subtitle' => __('Wildlife encounters in iconic reserves.'),
    'wide' => true,
])

<section class="site-gutter-x w-full max-w-none section-y-compact bg-white section-divider">
    <div class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-4">
        @forelse($items as $s)
            <article
                id="safari-{{ $s->slug }}"
                class="card-depth flex scroll-mt-28 flex-col overflow-hidden bg-white"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <a href="{{ route('safari.show', $s) }}" class="img-zoom-parent relative block aspect-[4/3] overflow-hidden bg-secondary/40">
                    @if($s->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($s->image) }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-full min-h-[10rem] items-center justify-center bg-gradient-to-br from-secondary/40 via-surface to-primary/[0.12]">
                            <svg class="h-12 w-12 text-primary/25" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 20h12M6 16h12M4 6h16M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2"/>
                            </svg>
                        </div>
                    @endif
                </a>
                <div class="flex flex-1 flex-col p-6">
                    <h2 class="text-xl font-semibold text-primary">
                        <a href="{{ route('safari.show', $s) }}" class="transition hover:text-primary/85 hover:underline">{{ $s->title }}</a>
                    </h2>
                    @if($s->duration)
                        <p class="mt-1 text-sm font-medium text-accent">{{ $s->duration }}</p>
                    @endif
                    <p class="mt-2 flex-1 text-sm text-ink/75">{{ \Illuminate\Support\Str::limit(strip_tags((string) $s->description), 160) }}</p>
                    <div class="mt-5 flex flex-col gap-2.5 border-t border-secondary/30 pt-5">
                        <a
                            href="{{ route('plan-my-safari', ['safari' => $s->slug]) }}"
                            class="btn-primary flex min-h-[2.75rem] w-full items-center justify-center gap-2 bg-gradient-to-r from-primary via-primary to-accent px-4 py-2.5 hover:brightness-110"
                        >
                            <svg class="h-4 w-4 shrink-0 opacity-95" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ __('Plan safari') }}
                        </a>
                        <a
                            href="{{ route('safari.show', $s) }}"
                            class="btn-secondary flex min-h-[2.75rem] w-full items-center justify-center px-4 py-2.5"
                        >{{ __('Explore') }}</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center text-ink/65">{{ __('Safari experiences will appear here.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
