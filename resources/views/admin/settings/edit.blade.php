@extends('layouts.admin')
@section('title', __('Settings'))
@section('heading', __('Settings'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Settings') }}@endsection
@section('content')
@php
    $previewLogoLight = \App\Models\SiteSetting::publicUrl($site_logo ?? '');
    $previewLogoDark = \App\Models\SiteSetting::publicUrl($site_logo_dark ?? '');
    $previewFavicon = \App\Models\SiteSetting::publicUrl($site_favicon ?? '');
    $previewMenuBg = \App\Models\SiteSetting::publicUrl($menu_background_image ?? '');
    $socialValues = [
        'social_facebook' => $social_facebook ?? '',
        'social_instagram' => $social_instagram ?? '',
        'social_youtube' => $social_youtube ?? '',
        'social_twitter' => $social_twitter ?? '',
        'social_tiktok' => $social_tiktok ?? '',
    ];
@endphp

<div class="mx-auto max-w-6xl">
    <p class="mb-8 max-w-2xl text-sm leading-relaxed text-ink/65">
        {{ __('Configure site branding, contact details shown to visitors, and social profiles. Uploads are stored securely; remove a file to revert to defaults where applicable.') }}
    </p>

    @if($errors->any())
        <div class="mb-8 rounded-2xl border border-red-200/80 bg-red-50/90 px-5 py-4 text-sm text-red-900 shadow-sm" role="alert">
            <p class="font-medium">{{ __('Please fix the following:') }}</p>
            <ul class="mt-2 list-inside list-disc space-y-0.5 text-red-800/95">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="pb-8">
        @csrf @method('PUT')

        <div class="grid gap-8 xl:grid-cols-12 xl:items-start xl:gap-10">
            {{-- Left: branding --}}
            <div class="space-y-8 xl:col-span-7">
                <section class="overflow-hidden rounded-2xl border border-secondary/50 bg-white shadow-card">
                    <header class="border-b border-secondary/40 bg-gradient-to-r from-primary/[0.06] to-transparent px-6 py-5 sm:px-8">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/12 text-primary" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h2 class="text-base font-semibold tracking-tight text-ink">{{ __('Branding & appearance') }}</h2>
                                <p class="mt-1 text-sm text-ink/55">{{ __('Logos, favicon, and optional header background for the public site.') }}</p>
                            </div>
                        </div>
                    </header>
                    <div class="space-y-8 px-6 py-8 sm:px-8">
                        <div class="grid gap-8 sm:grid-cols-2">
                            <div x-data="fileImagePreview(@js($previewLogoLight))" class="flex flex-col">
                                <label class="text-sm font-medium text-ink">{{ __('Logo — light backgrounds') }}</label>
                                <p class="mt-0.5 text-xs text-ink/50">{{ __('Admin sidebar, sign-in, etc.') }}</p>
                                <label class="mt-3 flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-secondary/55 bg-secondary/10 px-4 py-6 text-center transition hover:border-primary/35 hover:bg-primary/[0.04]">
                                    <input type="file" name="logo" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="sr-only" @change="pick($event)">
                                    <svg class="h-8 w-8 text-ink/35" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4-4 4m4-4v12"/></svg>
                                    <span class="mt-2 text-xs font-medium text-ink/75">{{ __('Choose image') }}</span>
                                    <span class="mt-1 text-[11px] text-ink/45">PNG, SVG, WebP · max 4MB</span>
                                </label>
                                <div x-show="preview" x-transition class="mt-4 rounded-xl border border-secondary/45 bg-white p-3 shadow-sm">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-ink/45">{{ __('Preview') }}</p>
                                    <img :src="preview" alt="" class="mt-2 h-14 max-w-[220px] object-contain object-left">
                                </div>
                                <label x-show="server" class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-ink/65">
                                    <input type="checkbox" name="remove_logo" value="1" class="rounded border-secondary text-primary focus:ring-primary/40">
                                    {{ __('Remove saved file') }}
                                </label>
                            </div>
                            <div x-data="fileImagePreview(@js($previewLogoDark))" class="flex flex-col">
                                <label class="text-sm font-medium text-ink">{{ __('Logo — dark backgrounds') }}</label>
                                <p class="mt-0.5 text-xs text-ink/50">{{ __('Public header & footer') }}</p>
                                <label class="mt-3 flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-slate-400/40 bg-slate-800/90 px-4 py-6 text-center transition hover:border-primary/45 hover:bg-slate-800">
                                    <input type="file" name="logo_dark" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml" class="sr-only" @change="pick($event)">
                                    <svg class="h-8 w-8 text-white/35" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4-4 4m4-4v12"/></svg>
                                    <span class="mt-2 text-xs font-medium text-white/80">{{ __('Choose image') }}</span>
                                    <span class="mt-1 text-[11px] text-white/45">PNG, SVG, WebP</span>
                                </label>
                                <div x-show="preview" x-transition class="mt-4 rounded-xl border border-slate-600 bg-slate-900 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-white/45">{{ __('Preview') }}</p>
                                    <img :src="preview" alt="" class="mt-2 h-14 max-w-[220px] object-contain object-left">
                                </div>
                                <label x-show="server" class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-ink/65">
                                    <input type="checkbox" name="remove_logo_dark" value="1" class="rounded border-secondary text-primary focus:ring-primary/40">
                                    {{ __('Remove saved file') }}
                                </label>
                            </div>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-2">
                            <div x-data="fileImagePreview(@js($previewFavicon))">
                                <label class="text-sm font-medium text-ink">{{ __('Favicon') }}</label>
                                <p class="mt-0.5 text-xs text-ink/50">{{ __('Browser tab icon') }}</p>
                                <label class="mt-3 flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-secondary/55 bg-secondary/10 px-4 py-5 text-center transition hover:border-primary/35 hover:bg-primary/[0.04]">
                                    <input type="file" name="favicon" accept=".ico,.png,.svg,image/x-icon,image/png,image/svg+xml" class="sr-only" @change="pick($event)">
                                    <span class="text-xs font-medium text-ink/75">{{ __('Choose .ico or PNG') }}</span>
                                </label>
                                <div x-show="preview" x-transition class="mt-4 inline-flex flex-col rounded-xl border border-secondary/45 bg-white p-3 shadow-sm">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-ink/45">{{ __('Preview') }}</p>
                                    <img :src="preview" alt="" class="mt-2 h-14 w-14 rounded-lg border border-secondary/30 object-contain bg-surface p-1">
                                </div>
                                <label x-show="server" class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-ink/65">
                                    <input type="checkbox" name="remove_favicon" value="1" class="rounded border-secondary text-primary focus:ring-primary/40">
                                    {{ __('Remove saved file') }}
                                </label>
                            </div>
                            <div x-data="fileImagePreview(@js($previewMenuBg))">
                                <label class="text-sm font-medium text-ink">{{ __('Menu background') }}</label>
                                <p class="mt-0.5 text-xs text-ink/50">{{ __('Top utility bar only') }}</p>
                                <label class="mt-3 flex cursor-pointer flex-col items-center rounded-xl border-2 border-dashed border-secondary/55 bg-secondary/10 px-4 py-5 text-center transition hover:border-primary/35 hover:bg-primary/[0.04]">
                                    <input type="file" name="menu_background" accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only" @change="pick($event)">
                                    <span class="text-xs font-medium text-ink/75">{{ __('Wide photo / texture') }}</span>
                                    <span class="mt-1 text-[11px] text-ink/45">JPEG, PNG, WebP</span>
                                </label>
                                <div x-show="preview" x-transition class="mt-4 rounded-xl border border-secondary/45 bg-secondary/15 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-ink/45">{{ __('Preview') }}</p>
                                    <img :src="preview" alt="" class="mt-2 h-24 w-full max-w-sm rounded-lg border border-secondary/40 object-cover">
                                </div>
                                <label x-show="server" class="mt-3 flex cursor-pointer items-center gap-2 text-xs text-ink/65">
                                    <input type="checkbox" name="remove_menu_background" value="1" class="rounded border-secondary text-primary focus:ring-primary/40">
                                    {{ __('Remove saved file') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Right: contact + social --}}
            <div class="space-y-8 xl:col-span-5">
                <section class="overflow-hidden rounded-2xl border border-secondary/50 bg-white shadow-card">
                    <header class="border-b border-secondary/40 bg-gradient-to-r from-secondary/25 to-transparent px-6 py-5 sm:px-8">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-secondary/40 text-ink/70" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h2 class="text-base font-semibold tracking-tight text-ink">{{ __('Contact') }}</h2>
                                <p class="mt-1 text-sm text-ink/55">{{ __('Shown in the header bar and contact page.') }}</p>
                            </div>
                        </div>
                    </header>
                    <div class="space-y-5 px-6 py-8 sm:px-8">
                        <div>
                            <label class="text-sm font-medium text-ink">{{ __('Operating hours') }}</label>
                            <input name="site_hours" value="{{ old('site_hours', $site_hours) }}" placeholder="{{ __('Mon – Fri : 8:00 – 16:00') }}" class="mt-1.5 w-full rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition placeholder:text-ink/35 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                            <p class="mt-1.5 text-xs text-ink/50">{{ __('Leave empty to hide in the header.') }}</p>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-ink">{{ __('Email') }}</label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $contact_email) }}" class="mt-1.5 w-full rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-ink">{{ __('Phone') }}</label>
                                <input name="contact_phone" value="{{ old('contact_phone', $contact_phone) }}" class="mt-1.5 w-full rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-ink">{{ __('Address') }}</label>
                                <textarea name="contact_address" rows="3" class="mt-1.5 w-full resize-y rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">{{ old('contact_address', $contact_address) }}</textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-ink">{{ __('Map embed URL') }}</label>
                                <input type="url" name="contact_map_embed_url" value="{{ old('contact_map_embed_url', $contact_map_embed_url ?? '') }}" placeholder="https://www.openstreetmap.org/export/embed.html?…" class="mt-1.5 w-full rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition placeholder:text-ink/35 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25">
                                <p class="mt-1.5 text-xs text-ink/50">{{ __('Paste an iframe src from Google Maps “Share → Embed” or an OpenStreetMap embed link. Leave empty to show a placeholder on the contact page.') }}</p>
                            </div>
                            <div class="border-t border-secondary/35 pt-6 sm:col-span-2">
                                <label class="text-sm font-medium text-ink">{{ __('Footer credits') }}</label>
                                <p class="mt-0.5 text-xs text-ink/50">{{ __('Full line for the public footer. Plain text or HTML (for example an anchor tag for “Powered by”). Only trusted admins should use HTML.') }}</p>
                                <textarea
                                    name="footer_credits"
                                    rows="3"
                                    class="mt-1.5 w-full resize-y rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 font-mono text-xs leading-relaxed transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25"
                                    placeholder='© 2026 … | Powered By &lt;a href="https://example.com" target="_blank" rel="noopener noreferrer"&gt;Partner&lt;/a&gt;'
                                >{{ old('footer_credits', $footer_credits ?? '') }}</textarea>
                                <p class="mt-1.5 text-xs text-ink/50">{{ __('Leave empty to use: current year, app name from .env, and the default “All rights reserved.” line.') }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-secondary/50 bg-white shadow-card">
                    <header class="border-b border-secondary/40 bg-gradient-to-r from-accent/15 to-transparent px-6 py-5 sm:px-8">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-accent/20 text-accent" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <div>
                                <h2 class="text-base font-semibold tracking-tight text-ink">{{ __('Social profiles') }}</h2>
                                <p class="mt-1 text-sm text-ink/55">{{ __('Full URLs, including https://') }}</p>
                            </div>
                        </div>
                    </header>
                    <div class="grid gap-4 px-6 py-8 sm:grid-cols-2 sm:px-8">
                        @foreach([
                            ['name' => 'social_facebook', 'label' => 'Facebook', 'placeholder' => 'https://facebook.com/…'],
                            ['name' => 'social_instagram', 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/…'],
                            ['name' => 'social_youtube', 'label' => 'YouTube', 'placeholder' => 'https://youtube.com/…'],
                            ['name' => 'social_twitter', 'label' => 'X (Twitter)', 'placeholder' => 'https://x.com/…'],
                            ['name' => 'social_tiktok', 'label' => 'TikTok', 'placeholder' => 'https://www.tiktok.com/@…'],
                        ] as $field)
                            <div class="{{ $field['name'] === 'social_tiktok' ? 'sm:col-span-2' : '' }}">
                                <label class="text-sm font-medium text-ink">{{ $field['label'] }}</label>
                                <input
                                    type="url"
                                    name="{{ $field['name'] }}"
                                    value="{{ old($field['name'], $socialValues[$field['name']] ?? '') }}"
                                    placeholder="{{ $field['placeholder'] }}"
                                    class="mt-1.5 w-full rounded-xl border border-secondary/60 bg-surface/50 px-4 py-3 text-sm transition placeholder:text-ink/35 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/25"
                                >
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <div class="mt-10 flex flex-col-reverse gap-4 border-t border-secondary/40 pt-8 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-ink/50">{{ __('Remember to save after changing files or URLs.') }}</p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl border border-secondary/60 bg-white px-5 py-3 text-sm font-medium text-ink/80 shadow-sm transition hover:border-primary/40 hover:bg-surface hover:text-ink">
                    {{ __('View site') }}
                    <svg class="ml-1.5 h-4 w-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <button type="submit" class="inline-flex min-w-[140px] items-center justify-center rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    {{ __('Save changes') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
