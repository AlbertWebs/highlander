@extends('layouts.site')

@section('title', filled($meta_title ?? null) ? $meta_title : __('Safari').' — '.config('app.name'))

@push('meta')
    @include('partials.seo-meta')
@endpush

@section('content')
@include('partials.page-hero', ['title' => __('Safari'), 'subtitle' => __('Wildlife encounters in iconic reserves.')])

<section class="site-gutter-x mx-auto max-w-7xl section-y-compact bg-white section-divider">
    <div class="grid gap-10 lg:grid-cols-2">
        @forelse($items as $s)
            <article
                class="card-depth flex flex-col overflow-hidden md:flex-row"
                data-aos="fade-up"
                data-aos-duration="800"
                data-aos-delay="{{ min(400, 100 * $loop->index) }}"
            >
                <div class="img-zoom-parent aspect-[4/3] w-full bg-secondary/40 md:w-2/5">
                    @if($s->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($s->image) }}" alt="" class="img-zoom-hover h-full w-full object-cover" loading="lazy">
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-6">
                    <h2 class="text-xl font-semibold text-primary">{{ $s->title }}</h2>
                    @if($s->duration)<p class="text-sm text-accent">{{ $s->duration }}</p>@endif
                    <p class="mt-3 flex-1 text-sm text-ink/80">{{ \Illuminate\Support\Str::limit($s->description, 280) }}</p>
                </div>
            </article>
        @empty
            <p class="col-span-full text-center">{{ __('Safari experiences will appear here.') }}</p>
        @endforelse
    </div>
    <div class="mt-10">{{ $items->links() }}</div>
</section>
@endsection
