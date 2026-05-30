@extends('layouts.admin')

@section('title', __('Edit mountain'))
@section('heading', __('Edit mountain'))
@section('breadcrumb')
    <a href="{{ route('admin.mountains.index') }}">{{ __('Mountains') }}</a>
    /
    <span class="text-ink/70">{{ $mountain->name }}</span>
    /
    {{ __('Edit') }}
@endsection

@section('content')
<div class="mx-auto max-w-5xl space-y-8 pb-24">
    <div class="flex flex-col gap-4 border-b border-secondary/40 pb-6 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-primary">{{ __('Mountain') }} #{{ $mountain->id }}</p>
            <h2 class="mt-1 text-xl font-semibold text-ink sm:text-2xl">{{ $mountain->name }}</h2>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink/65">
                {{ __('Update copy, elevation, and imagery. Changes apply to the public Mountains page when this entry is visible.') }}
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            <a
                href="{{ route('admin.mountains.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-secondary/60 bg-white px-4 py-2.5 text-sm font-medium text-ink shadow-sm transition hover:bg-surface"
            >
                {{ __('← Back to list') }}
            </a>
            <a
                href="{{ route('mountains.show', $mountain) }}"
                class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-accent px-4 py-2.5 text-sm font-semibold text-ink shadow-sm ring-1 ring-black/[0.04] transition hover:bg-accent/90"
                target="_blank"
                rel="noopener noreferrer"
            >
                {{ __('View on site') }}
                <svg class="h-3.5 w-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 shadow-sm" role="alert">
            <p class="font-semibold">{{ __('Please fix the following:') }}</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-3 lg:items-start lg:gap-10">
        <form
            action="{{ route('admin.mountains.update', $mountain) }}"
            method="post"
            enctype="multipart/form-data"
            class="space-y-6 lg:col-span-2"
            onsubmit="if (window.tinymce) tinymce.triggerSave();"
        >
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
                <h3 class="text-base font-semibold text-ink">{{ __('Details') }}</h3>
                <p class="mt-1 text-sm text-ink/55">{{ __('Name and description appear on the public card.') }}</p>

                <div class="mt-6 space-y-5">
                    <div>
                        <label for="mountain-name" class="text-sm font-medium text-ink">{{ __('Name') }} <span class="text-red-600">*</span></label>
                        <input
                            id="mountain-name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name', $mountain->name) }}"
                            class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                        >
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="text-sm font-medium text-ink">{{ __('URL slug') }}</p>
                        <p class="mt-1 rounded-xl border border-dashed border-secondary/60 bg-surface/60 px-4 py-3 font-mono text-sm text-ink/80">{{ $mountain->slug }}</p>
                        <p class="mt-1.5 text-xs text-ink/50">{{ __('Updated automatically when you save a new name.') }}</p>
                    </div>

                    <div>
                        <label for="mountain-description" class="text-sm font-medium text-ink">{{ __('Description') }}</label>
                        <p class="mt-1 text-xs text-ink/50">{{ __('Rich text: bold, lists, and links. Shown on the mountain detail page.') }}</p>
                        <textarea
                            id="mountain-description"
                            name="description"
                            rows="10"
                            class="form-input-interactive mt-1.5 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 font-mono text-sm shadow-sm"
                            placeholder="{{ __('Summit routes, scenery, best season…') }}"
                        >{{ old('description', $mountain->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mountain-elevation" class="text-sm font-medium text-ink">{{ __('Elevation (metres)') }}</label>
                        <input
                            id="mountain-elevation"
                            name="elevation_m"
                            type="number"
                            min="0"
                            max="9000"
                            value="{{ old('elevation_m', $mountain->elevation_m) }}"
                            class="form-input-interactive mt-1.5 w-full max-w-xs rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm"
                        >
                        @error('elevation_m')
                            <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-secondary/40 bg-surface/50 px-4 py-3">
                        <label class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', $mountain->is_active))
                                class="mt-1 rounded border-secondary/60 text-primary focus:ring-primary/30"
                            >
                            <span>
                                <span class="text-sm font-medium text-ink">{{ __('Visible on public site') }}</span>
                                <span class="mt-0.5 block text-xs text-ink/55">{{ __('Turn off to hide this mountain without deleting it.') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8">
                <h3 class="text-base font-semibold text-ink">{{ __('Hero image') }}</h3>
                <p class="mt-1 text-sm text-ink/55">{{ __('Shown on the card on /mountains. JPEG or PNG, max 5 MB.') }}</p>

                @if($mountain->image)
                    <div class="mt-4 overflow-hidden rounded-xl border border-secondary/40 bg-neutral-100">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mountain->image) }}"
                            alt=""
                            class="aspect-[16/10] w-full object-cover"
                            width="800"
                            height="500"
                        >
                    </div>
                @else
                    <div class="mt-4 flex aspect-[16/10] items-center justify-center rounded-xl border border-dashed border-secondary/60 bg-surface/60 text-sm text-ink/45">
                        {{ __('No image yet — upload one below.') }}
                    </div>
                @endif

                <div class="mt-4">
                    <label for="mountain-image" class="text-sm font-medium text-ink">{{ $mountain->image ? __('Replace image') : __('Upload image') }}</label>
                    <input
                        id="mountain-image"
                        name="image"
                        type="file"
                        accept="image/*"
                        class="mt-1.5 block w-full text-sm text-ink/80 file:mr-4 file:rounded-lg file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/15"
                    >
                    @error('image')
                        <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t border-secondary/30 pt-6">
                <button
                    type="submit"
                    class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                >
                    {{ __('Save changes') }}
                </button>
                <a
                    href="{{ route('admin.mountains.index') }}"
                    class="inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-secondary/50 px-6 py-2.5 text-sm font-medium text-ink transition hover:bg-secondary/20"
                >
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>

        <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-2xl border border-secondary/50 bg-white p-5 shadow-soft">
                <h3 class="text-sm font-semibold text-ink">{{ __('Quick reference') }}</h3>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-4 border-b border-secondary/30 pb-2">
                        <dt class="text-ink/55">{{ __('Updated') }}</dt>
                        <dd class="font-medium text-ink">{{ $mountain->updated_at->format('M j, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink/55">{{ __('Created') }}</dt>
                        <dd class="font-medium text-ink">{{ $mountain->created_at->format('M j, Y') }}</dd>
                    </div>
                </dl>
            </div>
            <div class="rounded-2xl border border-primary/20 bg-primary/[0.04] p-5">
                <h3 class="text-sm font-semibold text-primary">{{ __('Tip') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink/75">
                    {{ __('Strong photos and accurate elevation help guests compare peaks. Long descriptions can mention routes and typical trip length.') }}
                </p>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#mountain-description',
            height: 380,
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | styles | bold italic | bullist numlist | link | code',
            entity_encoding: 'raw',
        });
    }
});
</script>
@endpush
