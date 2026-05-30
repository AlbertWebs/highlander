@php
    if (! isset($destination)) {
        $destination = new \App\Models\Destination(['is_active' => true, 'sort_order' => 0]);
    }
    $isEdit = $destination->exists;
    $previewUrl = ($isEdit && $destination->image)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image)
        : '';
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="dest_name" class="block text-sm font-medium text-ink">{{ __('Name') }} <span class="text-red-600">*</span></label>
        <input
            id="dest_name"
            type="text"
            name="name"
            value="{{ old('name', $destination->name) }}"
            required
            maxlength="255"
            class="mt-1 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25"
            placeholder="{{ __('e.g. Maasai Mara') }}"
        >
        @error('name')
            <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
        @if($isEdit && filled($destination->slug))
            <p class="mt-2 text-xs text-ink/55">
                {{ __('URL slug') }}:
                <code class="rounded bg-secondary/40 px-1.5 py-0.5 font-mono text-ink/80">{{ $destination->slug }}</code>
                <span class="text-ink/45"> — {{ __('updates automatically when you change the name') }}</span>
            </p>
        @endif
    </div>

    <div class="md:col-span-2">
        <label for="dest_description" class="block text-sm font-medium text-ink">{{ __('Description') }}</label>
        <p class="mt-0.5 text-xs text-ink/55">{{ __('Rich text for cards, Explore Africa, and the destination detail page. Use the toolbar or Source code for HTML.') }}</p>
        <textarea
            id="dest_description"
            name="description"
            rows="10"
            class="mt-2 w-full rounded-xl border-secondary/60 px-4 py-3 font-mono text-sm shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25"
            placeholder="{{ __('Intro, highlights, best season…') }}"
        >{{ old('description', $destination->description) }}</textarea>
        @error('description')
            <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="dest_sort" class="block text-sm font-medium text-ink">{{ __('Sort order') }}</label>
        <p class="mt-0.5 text-xs text-ink/55">{{ __('Higher numbers appear first in the destinations list.') }}</p>
        <input
            id="dest_sort"
            type="number"
            name="sort_order"
            value="{{ old('sort_order', $destination->sort_order ?? 0) }}"
            min="0"
            max="65535"
            class="mt-2 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25"
        >
        @error('sort_order')
            <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-end pb-1">
        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-secondary/40 bg-surface/50 px-4 py-4">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $destination->is_active ?? true))
                class="mt-0.5 rounded border-secondary text-primary focus:ring-2 focus:ring-primary/30"
            >
            <span>
                <span class="block text-sm font-medium text-ink">{{ __('Active') }}</span>
                <span class="mt-0.5 block text-xs text-ink/55">{{ __('Inactive destinations are hidden from the public site.') }}</span>
            </span>
        </label>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-ink">{{ __('Card image') }}</label>
        <p class="mt-0.5 text-xs text-ink/55">{{ __('Used on destination cards. JPEG, PNG, WebP or GIF — max 5 MB.') }}</p>
        <div x-data="fileImagePreview(@js($previewUrl))" class="mt-3">
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
                    <img :src="preview" alt="" class="max-h-48 w-full rounded-lg object-cover object-center sm:max-h-56">
                </div>
            </div>
        </div>
        @error('image')
            <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
    </div>
</div>
