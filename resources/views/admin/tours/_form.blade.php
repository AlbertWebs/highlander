<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">{{ __('Title') }}</label>
        <input type="text" name="title" value="{{ old('title', $tour->title ?? '') }}" required class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">{{ __('Description') }}</label>
        <textarea name="description" rows="5" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old('description', $tour->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Price') }}</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $tour->price ?? '') }}" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Duration (days)') }}</label>
        <input type="number" name="duration_days" value="{{ old('duration_days', $tour->duration_days ?? '') }}" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
    </div>
    <div>
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $tour->is_active ?? true)) class="rounded border-secondary text-primary focus:ring-primary">
            {{ __('Active') }}
        </label>
    </div>
    <div>
        <label class="flex items-center gap-2 text-sm font-medium">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $tour->is_featured ?? false)) class="rounded border-secondary text-primary focus:ring-primary">
            {{ __('Featured') }}
        </label>
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Main menu (experiences)') }}</label>
        <select name="nav_bucket" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
            @foreach(\App\Models\Tour::NAV_BUCKETS as $bucket)
                <option value="{{ $bucket }}" @selected(old('nav_bucket', $tour->nav_bucket ?? \App\Models\Tour::NAV_SAFARI) === $bucket)>
                    @if($bucket === \App\Models\Tour::NAV_SAFARI)
                        {{ __('Safari') }}
                    @elseif($bucket === \App\Models\Tour::NAV_MOUNTAIN_SAFARI)
                        {{ __('Mountain safari') }}
                    @elseif($bucket === \App\Models\Tour::NAV_EXPLORE_AFRICA)
                        {{ __('Explore Africa') }}
                    @else
                        {{ $bucket }}
                    @endif
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-ink/55">{{ __('Lists this itinerary under the matching top menu. Public URL stays /experiences/{slug}. Use Mountain safari only for peak treks and climbing routes; savanna trips belong under Safari or Explore Africa.') }}</p>
        @error('nav_bucket')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2 rounded-2xl border border-secondary/40 bg-secondary/10 p-5">
        <p class="text-sm font-medium text-ink">{{ __('Mountain & destination hubs') }}</p>
        <p class="mt-1 text-xs leading-relaxed text-ink/55">{{ __('Optional links for related tours on peak and destination pages. Heuristic matching still applies for unlinked tours. Mountain hub lists the same peaks as the public site header Mountains menu.') }}</p>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">{{ __('Mountain hub') }}</label>
                <select name="mountain_id" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">{{ __('None') }}</option>
                    @foreach(($mountains ?? collect()) as $m)
                        <option value="{{ $m->id }}" @selected((string) old('mountain_id', $tour->mountain_id ?? '') === (string) $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
                @error('mountain_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-2 text-xs text-ink/50">
                    <a href="{{ route('admin.mountains.create') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Add mountain') }}</a>
                    @if(($mountains ?? collect())->isNotEmpty())
                        <span class="text-ink/40"> · </span>
                        <a href="{{ route('admin.mountains.index') }}" target="_blank" rel="noopener noreferrer" class="text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('All mountains') }}</a>
                    @endif
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Destination hub') }}</label>
                <select name="destination_id" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">{{ __('None') }}</option>
                    @foreach(($destinations ?? collect()) as $d)
                        <option value="{{ $d->id }}" @selected((string) old('destination_id', $tour->destination_id ?? '') === (string) $d->id)>{{ $d->name }}</option>
                    @endforeach
                </select>
                @error('destination_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-2 text-xs text-ink/50">
                    <a href="{{ route('admin.destinations.create') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Add destination') }}</a>
                    @if(($destinations ?? collect())->isNotEmpty())
                        <span class="text-ink/40"> · </span>
                        <a href="{{ route('admin.destinations.index') }}" target="_blank" rel="noopener noreferrer" class="text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('All destinations') }}</a>
                    @endif
                </p>
            </div>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Sort order') }}</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $tour->sort_order ?? 0) }}" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
    </div>
    @php
        $featuredMedia = old('featured_media_type', $tour->featured_media_type ?? 'image');
    @endphp
    <div class="md:col-span-2 rounded-2xl border border-secondary/50 bg-surface/80 p-5" x-data="{ media: @js($featuredMedia) }">
        <p class="text-sm font-medium text-ink">{{ __('Featured experience card (homepage)') }}</p>
        <p class="mt-1 text-xs leading-relaxed text-ink/55">{{ __('Used when this tour is marked Featured. Choose a still image or a looping background video (Vimeo page URL or direct MP4/WebM link).') }}</p>
        <div class="mt-4 flex flex-wrap gap-6">
            <label class="flex cursor-pointer items-center gap-2 text-sm font-medium">
                <input type="radio" name="featured_media_type" value="image" x-model="media" class="border-secondary text-primary focus:ring-primary">
                {{ __('Image') }}
            </label>
            <label class="flex cursor-pointer items-center gap-2 text-sm font-medium">
                <input type="radio" name="featured_media_type" value="video" x-model="media" class="border-secondary text-primary focus:ring-primary">
                {{ __('Video') }}
            </label>
        </div>
        <div class="mt-4" x-show="media === 'image'" style="display: {{ $featuredMedia === 'video' ? 'none' : 'block' }}">
            <label class="block text-sm font-medium">{{ __('Image file') }}</label>
            <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm">
            @if($tour->imageUrl())
                <img src="{{ $tour->imageUrl() }}" alt="" class="mt-2 h-24 rounded-lg object-cover">
            @endif
        </div>
        <div class="mt-4" x-show="media === 'video'" style="display: {{ $featuredMedia === 'video' ? 'block' : 'none' }}">
            <label class="block text-sm font-medium">{{ __('Video URL') }}</label>
            <input
                type="url"
                name="featured_video_url"
                value="{{ old('featured_video_url', $tour->featured_video_url ?? '') }}"
                class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 font-mono text-sm shadow-sm focus:border-primary focus:ring-primary"
                placeholder="https://vimeo.com/… {{ __('or') }} https://…mp4"
                autocomplete="off"
            >
            @error('featured_video_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1.5 text-xs text-ink/50">{{ __('Vimeo: paste the page or player link. Direct file: must be publicly reachable (HTTPS).') }}</p>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Meta title') }}</label>
        <input type="text" name="meta_title" value="{{ old('meta_title', $tour->meta_title ?? '') }}" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
    </div>
    <div>
        <label class="block text-sm font-medium">{{ __('Meta description') }}</label>
        <input type="text" name="meta_description" value="{{ old('meta_description', $tour->meta_description ?? '') }}" class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
    </div>
</div>
