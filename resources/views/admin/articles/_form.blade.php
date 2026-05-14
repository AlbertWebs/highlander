@php
    $existingFeaturedUrl = ($article->id ?? null) && $article->featured_image
        ? (string) ($article->featuredImageUrl() ?? '')
        : '';
@endphp

<div class="space-y-8">
    <section class="rounded-2xl border border-secondary/50 bg-surface/40 p-6 shadow-sm ring-1 ring-black/[0.02] md:p-8" aria-labelledby="article-content-heading">
        <h2 id="article-content-heading" class="text-sm font-semibold uppercase tracking-wide text-ink/70">{{ __('Content') }}</h2>
        <p class="mt-1 text-xs text-ink/50">{{ __('Title and body appear on the public article page. Excerpt is used in listings and cards.') }}</p>
        <div class="mt-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-ink" for="article-title">{{ __('Title') }}</label>
                <input id="article-title" type="text" name="title" required value="{{ old('title', $article->title ?? '') }}" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                @error('title')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink" for="article-excerpt">{{ __('Excerpt') }}</label>
                <textarea id="article-excerpt" name="excerpt" rows="3" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="{{ __('Short summary for listings (optional)') }}">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                @error('excerpt')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink" for="article-body">{{ __('Body') }}</label>
                <textarea id="article-body" name="body" rows="14" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 font-mono text-sm shadow-sm focus:border-primary focus:ring-primary">{{ old('body', $article->body ?? '') }}</textarea>
                @error('body')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section
        class="rounded-2xl border border-secondary/50 bg-surface/40 p-6 shadow-sm ring-1 ring-black/[0.02] md:p-8"
        aria-labelledby="article-publishing-heading"
        x-data="{
            existingUrl: @js($existingFeaturedUrl),
            blobUrl: '',
            displayUrl() { return this.blobUrl || this.existingUrl || ''; },
            onFeaturedFile(event) {
                if (this.blobUrl) {
                    URL.revokeObjectURL(this.blobUrl);
                    this.blobUrl = '';
                }
                const file = event.target.files && event.target.files[0];
                if (!file || !file.type.startsWith('image/')) {
                    return;
                }
                this.blobUrl = URL.createObjectURL(file);
            }
        }"
    >
        <h2 id="article-publishing-heading" class="text-sm font-semibold uppercase tracking-wide text-ink/70">{{ __('Publishing') }}</h2>
        <div class="mt-6 grid gap-8 lg:grid-cols-2 lg:items-start">
            <div>
                <label class="block text-sm font-medium text-ink" for="article-published-at">{{ __('Published at') }}</label>
                <input id="article-published-at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                <p class="mt-2 text-xs text-ink/50">{{ __('Leave empty to keep the article unpublished until you set a date.') }}</p>
                @error('published_at')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                <label class="mt-6 flex cursor-pointer items-start gap-3 rounded-xl border border-secondary/40 bg-white/80 px-4 py-3 shadow-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $article->is_active ?? true)) class="mt-0.5 rounded border-secondary text-primary focus:ring-primary">
                    <span>
                        <span class="block text-sm font-medium text-ink">{{ __('Active') }}</span>
                        <span class="mt-0.5 block text-xs text-ink/50">{{ __('Inactive articles are hidden from the public site.') }}</span>
                    </span>
                </label>
            </div>

            <div class="rounded-2xl border border-secondary/40 bg-white/90 p-5 shadow-sm">
                <label class="block text-sm font-medium text-ink" for="article-featured-image">{{ __('Featured image') }}</label>
                <p class="mt-1 text-xs text-ink/50">{{ __('JPEG, PNG, or WebP. Max 5 MB. Used as the article hero and in social previews.') }}</p>

                <div class="mt-4">
                    <input id="article-featured-image" x-ref="featuredInput" type="file" name="featured_image" accept="image/*" class="sr-only" @change="onFeaturedFile($event)">
                    <label for="article-featured-image" class="flex cursor-pointer flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-secondary/50 bg-secondary/5 px-4 py-8 text-center transition hover:border-primary/45 hover:bg-primary/[0.04]">
                        <span class="text-sm font-medium text-primary">{{ __('Choose image') }}</span>
                        <span class="text-xs text-ink/45">{{ __('PNG, JPEG, or WebP · max 5 MB') }}</span>
                    </label>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        class="text-xs font-medium text-ink/60 underline decoration-secondary/60 underline-offset-2 hover:text-ink"
                        @click="if (blobUrl) { URL.revokeObjectURL(blobUrl); blobUrl = '' }; $refs.featuredInput.value = ''"
                    >{{ __('Clear selection') }}</button>
                    <span class="text-xs text-ink/40" x-show="blobUrl" x-cloak>{{ __('New file selected — save to upload.') }}</span>
                </div>

                <template x-if="displayUrl() !== ''">
                    <div
                        class="mt-5 overflow-hidden rounded-xl border border-secondary/30 bg-secondary/10"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-[0.98]"
                        x-transition:enter-end="opacity-100 scale-100"
                    >
                        <div class="relative aspect-[21/9] max-h-52 w-full bg-secondary/20">
                            <img
                                :src="displayUrl()"
                                alt=""
                                class="h-full w-full object-cover"
                                width="1200"
                                height="514"
                                loading="lazy"
                            >
                        </div>
                        <p class="border-t border-secondary/30 bg-white/90 px-3 py-2 text-center text-xs text-ink/55" x-text="blobUrl ? @js(__('Preview — not saved until you submit the form.')) : @js(__('Current featured image'))"></p>
                    </div>
                </template>

                @error('featured_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-secondary/50 bg-surface/40 p-6 shadow-sm ring-1 ring-black/[0.02] md:p-8" aria-labelledby="article-seo-heading">
        <h2 id="article-seo-heading" class="text-sm font-semibold uppercase tracking-wide text-ink/70">{{ __('SEO') }}</h2>
        <p class="mt-1 text-xs text-ink/50">{{ __('Optional overrides for search results; defaults to title and excerpt when left blank.') }}</p>
        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-ink" for="article-meta-title">{{ __('Meta title') }}</label>
                <input id="article-meta-title" type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title ?? '') }}" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                @error('meta_title')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-ink" for="article-meta-description">{{ __('Meta description') }}</label>
                <input id="article-meta-description" type="text" name="meta_description" value="{{ old('meta_description', $article->meta_description ?? '') }}" class="mt-1.5 w-full rounded-xl border-secondary/60 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                @error('meta_description')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </section>
</div>
