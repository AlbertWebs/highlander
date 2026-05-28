@php
    if (! isset($safariExperience)) {
        $safariExperience = new \App\Models\SafariExperience(['is_active' => true, 'sort_order' => 0]);
    }
    $isEdit = $safariExperience->exists;
    $previewUrl = ($isEdit && $safariExperience->image)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($safariExperience->image)
        : '';
@endphp

<div class="space-y-8">
    <div id="linked-itineraries" class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <h3 class="text-base font-semibold text-ink">{{ __('Content') }}</h3>
        <p class="mt-1 text-sm text-ink/55">{{ __('Title and copy appear on the public Safari page cards.') }}</p>

        <div class="mt-6 space-y-5">
            <div>
                <label for="safari-title" class="text-sm font-medium text-ink">{{ __('Title') }} <span class="text-red-600">*</span></label>
                <input
                    id="safari-title"
                    name="title"
                    type="text"
                    required
                    maxlength="255"
                    value="{{ old('title', $safariExperience->title) }}"
                    class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                    placeholder="{{ __('e.g. Classic Serengeti & Ngorongoro') }}"
                >
                @error('title')
                    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                @enderror
                @if($isEdit && filled($safariExperience->slug))
                    <p class="mt-2 text-xs text-ink/55">
                        {{ __('Public URL') }}:
                        <a href="{{ route('safari.show', $safariExperience) }}" target="_blank" rel="noopener noreferrer" class="font-mono text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ parse_url(route('safari.show', $safariExperience), PHP_URL_PATH) }}</a>
                        <span class="text-ink/45"> - {{ __('updates when you change the title') }}</span>
                    </p>
                @endif
            </div>

            <div>
                <label for="safari-description" class="text-sm font-medium text-ink">{{ __('Description') }}</label>
                <textarea
                    id="safari-description"
                    name="description"
                    rows="6"
                    class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                    placeholder="{{ __('Highlights, parks, and what makes this style special…') }}"
                >{{ old('description', $safariExperience->description) }}</textarea>
                <p class="mt-1.5 text-xs text-ink/50">{{ __('Short paragraph for the public card. Plain text is fine.') }}</p>
                @error('description')
                    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="safari-duration" class="text-sm font-medium text-ink">{{ __('Duration') }}</label>
                <input
                    id="safari-duration"
                    name="duration"
                    type="text"
                    maxlength="255"
                    value="{{ old('duration', $safariExperience->duration) }}"
                    class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                    placeholder="{{ __('e.g. 5 days / 4 nights') }}"
                >
                <p class="mt-1.5 text-xs text-ink/50">{{ __('Optional line under the title on the card.') }}</p>
                @error('duration')
                    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <h3 class="text-base font-semibold text-ink">{{ __('Order & visibility') }}</h3>
        <p class="mt-1 text-sm text-ink/55">{{ __('Lower sort numbers appear first on the Safari page.') }}</p>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 sm:items-end">
            <div>
                <label for="safari-sort" class="text-sm font-medium text-ink">{{ __('Sort order') }}</label>
                <input
                    id="safari-sort"
                    name="sort_order"
                    type="number"
                    min="0"
                    max="65535"
                    value="{{ old('sort_order', $safariExperience->sort_order ?? 0) }}"
                    class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                >
                @error('sort_order')
                    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:pb-1">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-secondary/40 bg-surface/50 px-4 py-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $safariExperience->is_active ?? true))
                        class="mt-0.5 rounded border-secondary text-primary focus:ring-2 focus:ring-primary/30"
                    >
                    <span>
                        <span class="block text-sm font-medium text-ink">{{ __('Visible on public site') }}</span>
                        <span class="mt-0.5 block text-xs text-ink/55">{{ __('Hidden items stay in the admin list but do not appear on /safari.') }}</span>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <h3 class="text-base font-semibold text-ink">{{ __('Linked itineraries') }}</h3>
        <p class="mt-1 text-sm text-ink/55">{{ __('Select tour itineraries that belong to this safari style. These links are used on safari detail pages.') }}</p>
        <p class="mt-1 text-xs text-ink/50">
            <a href="{{ route('admin.tours.create') }}" class="font-semibold text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Create itinerary') }}</a>
            <span class="text-ink/35"> · </span>
            <a href="{{ route('admin.tours.index') }}" class="text-primary underline decoration-primary/30 underline-offset-2 hover:decoration-primary">{{ __('Manage all itineraries') }}</a>
        </p>

        @php
            $selectedTours = collect(old('tour_ids', $isEdit ? $safariExperience->tours->pluck('id')->all() : []))
                ->map(fn ($id) => (int) $id)
                ->all();
        @endphp

        <div class="mt-5 max-h-72 space-y-2 overflow-y-auto rounded-xl border border-secondary/45 bg-surface/40 p-3">
            @forelse(($tours ?? collect()) as $tour)
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-transparent px-3 py-2.5 transition hover:border-secondary/50 hover:bg-white">
                    <input
                        type="checkbox"
                        name="tour_ids[]"
                        value="{{ $tour->id }}"
                        @checked(in_array($tour->id, $selectedTours, true))
                        class="mt-0.5 rounded border-secondary text-primary focus:ring-2 focus:ring-primary/30"
                    >
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-medium text-ink">{{ $tour->title }}</span>
                        <span class="block text-xs text-ink/55">{{ $tour->slug }}@if($tour->duration_days) · {{ __(':days days', ['days' => $tour->duration_days]) }}@endif</span>
                    </span>
                </label>
            @empty
                <p class="px-1 py-2 text-sm text-ink/60">{{ __('No active itineraries yet. Create one first, then link it here.') }}</p>
            @endforelse
        </div>
        @error('tour_ids')
            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
        @error('tour_ids.*')
            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
        <h3 class="text-base font-semibold text-ink">{{ __('Image') }}</h3>
        <p class="mt-1 text-sm text-ink/55">{{ __('Landscape photo for the card. JPEG, PNG, WebP or GIF - max 5 MB.') }}</p>

        <div x-data="fileImagePreview(@js($previewUrl))" class="mt-4">
            <label class="flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-secondary/55 bg-secondary/10 px-4 py-8 text-center transition hover:border-primary/35 hover:bg-primary/[0.04] sm:flex-row sm:justify-center sm:gap-4 sm:py-6">
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only" @change="pick($event)">
                <svg class="h-10 w-10 shrink-0 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="mt-3 text-sm font-medium text-ink/80 sm:mt-0">{{ __('Choose or replace image') }}</span>
            </label>
            <div x-show="preview" x-transition class="mt-4 overflow-hidden rounded-xl border border-secondary/45 bg-white shadow-sm">
                <p class="border-b border-secondary/30 bg-surface/60 px-4 py-2 text-[11px] font-semibold uppercase tracking-wide text-ink/45">{{ __('Preview') }}</p>
                <div class="p-4">
                    <img :src="preview" alt="" class="max-h-56 w-full rounded-lg object-cover object-center">
                </div>
            </div>
        </div>
        @error('image')
            <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>
</div>
