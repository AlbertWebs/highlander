@props([
    'categories' => collect(),
    'uploadUrl' => '',
    'adminGalleryBase' => '',
    'labels' => [],
])
<div
    class="mb-10 rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8"
    x-data="galleryDropzone({ uploadUrl: @js($uploadUrl), maxFiles: 30, adminGalleryBase: @js($adminGalleryBase), labels: @js($labels) })"
>
    <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-ink">{{ __('Upload images') }}</h2>
            <p class="mt-1 max-w-2xl text-sm text-ink/65">{{ __('Add many photos at once. Choose a default category, then set title and category per image if you like, and upload.') }}</p>
        </div>
        <p class="text-xs text-ink/45 sm:text-right">{{ __('JPEG, PNG, WebP · max :size MB each · up to :n files', ['size' => '8', 'n' => '30']) }}</p>
    </div>

    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-end">
        <div class="min-w-0 flex-1">
            <label class="text-sm font-medium text-ink">{{ __('Default category') }}</label>
            <select x-model="defaultCategoryId" class="mt-1 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm">
                <option value="">{{ __('Uncategorized') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div
        class="mt-6 flex min-h-[10rem] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-4 py-10 text-center transition"
        :class="dragging ? 'border-primary bg-primary/5' : 'border-secondary/70 bg-surface/50 hover:border-primary/40'"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
        @click="$refs.bulkInput.click()"
        role="button"
        tabindex="0"
        @keydown.enter.prevent="$refs.bulkInput.click()"
    >
        <input type="file" x-ref="bulkInput" class="hidden" accept="image/*" multiple @change="addFiles($event.target.files); $event.target.value = ''">
        <svg class="mx-auto h-10 w-10 text-ink/35" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
        </svg>
        <p class="mt-3 text-sm font-medium text-ink">{{ __('Drop images here') }}</p>
        <p class="mt-1 text-xs text-ink/55">{{ __('or click to browse — up to 30 files') }}</p>
    </div>

    <template x-if="error">
        <p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800" x-text="error"></p>
    </template>

    <div x-show="uploading" x-cloak class="mt-6 space-y-2 rounded-xl border border-primary/25 bg-primary/[0.06] p-4">
        <div class="flex items-center justify-between gap-3 text-xs font-medium text-ink/80">
            <span x-text="uploadStatus || '{{ __('Sending files…') }}'"></span>
            <span class="tabular-nums text-primary" x-text="uploadProgress + '%'"></span>
        </div>
        <div class="h-2.5 w-full overflow-hidden rounded-full bg-secondary/50">
            <div
                class="h-full rounded-full bg-primary transition-[width] duration-150 ease-out"
                :style="'width:' + uploadProgress + '%'"
            ></div>
        </div>
        <p class="text-[0.7rem] text-ink/55">{{ __('Large batches may take a moment. Keep this tab open.') }}</p>
    </div>

    <div
        x-show="lastMessage && !uploading"
        x-cloak
        x-transition
        class="mt-4 rounded-xl border border-primary/30 bg-tint-green/60 px-4 py-3 text-sm font-medium text-primary"
        role="status"
    >
        <span x-text="lastMessage"></span>
    </div>

    <div class="mt-6 space-y-4" x-show="rows.length > 0" x-cloak>
        <template x-for="row in rows" :key="row._id">
            <div class="flex flex-col gap-3 rounded-xl border border-secondary/40 bg-surface/40 p-4 sm:flex-row sm:items-start">
                <img :src="row.preview" alt="" class="h-24 w-36 shrink-0 rounded-lg object-cover ring-1 ring-black/5">
                <div class="min-w-0 flex-1 space-y-3">
                    <div>
                        <label class="text-xs font-medium text-ink/70">{{ __('Title') }}</label>
                        <input type="text" x-model="row.title" class="mt-1 w-full rounded-lg border border-secondary/60 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-ink/70">{{ __('Category') }}</label>
                        <select x-model="row.categoryId" class="mt-1 w-full rounded-lg border border-secondary/60 px-3 py-2 text-sm">
                            <option value="">{{ __('Uncategorized') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" @click="removeRow(row._id)" class="shrink-0 rounded-lg border border-secondary/50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-50">{{ __('Remove') }}</button>
            </div>
        </template>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3" x-show="rows.length > 0" x-cloak>
        <button
            type="button"
            @click="submit()"
            :disabled="uploading"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-60"
        >
            <svg x-show="uploading" x-cloak class="h-4 w-4 shrink-0 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-show="!uploading">{{ __('Upload all') }}</span>
            <span x-show="uploading" x-cloak>{{ __('Uploading…') }}</span>
        </button>
        <button type="button" @click="clearAll()" class="text-sm font-medium text-ink/70 underline-offset-2 hover:text-ink hover:underline">{{ __('Clear list') }}</button>
    </div>
</div>
