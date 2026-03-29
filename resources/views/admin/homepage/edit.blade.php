@extends('layouts.admin')
@section('title', __('Homepage'))
@section('heading', __('Homepage content'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('Homepage') }}@endsection

@php
    $field = 'mt-1 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm';
    $label = 'text-sm font-medium text-ink';
    $hint = 'mt-1 text-xs leading-relaxed text-ink/55';
    $section = 'rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8';
    $sectionTitle = 'text-base font-semibold text-ink';
@endphp

@section('content')
<div class="mx-auto max-w-6xl space-y-6 pb-24">
    <nav class="flex flex-wrap gap-2 border-b border-secondary/40 pb-4 text-sm" aria-label="{{ __('Page sections') }}">
        <a href="#section-hero" class="rounded-full bg-secondary/35 px-3 py-1.5 font-medium text-ink/90 transition hover:bg-primary/15 hover:text-primary">{{ __('Hero') }}</a>
        <a href="#welcome-section" class="rounded-full bg-secondary/35 px-3 py-1.5 font-medium text-ink/90 transition hover:bg-primary/15 hover:text-primary">{{ __('Welcome') }}</a>
        <a href="#section-why-choose" class="rounded-full bg-secondary/35 px-3 py-1.5 font-medium text-ink/90 transition hover:bg-primary/15 hover:text-primary">{{ __('Why choose us') }}</a>
        <a href="#section-cta" class="rounded-full bg-secondary/35 px-3 py-1.5 font-medium text-ink/90 transition hover:bg-primary/15 hover:text-primary">{{ __('Call to action') }}</a>
        <a href="{{ route('admin.about-page.edit') }}" class="rounded-full bg-secondary/35 px-3 py-1.5 font-medium text-ink/90 transition hover:bg-primary/15 hover:text-primary">{{ __('About page') }}</a>
    </nav>

    <form action="{{ route('admin.homepage.update') }}" method="post" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Hero --}}
        <section id="section-hero" class="{{ $section }} scroll-mt-6">
            <h2 class="{{ $sectionTitle }}">{{ __('Hero') }}</h2>
            <p class="mt-1 max-w-3xl text-sm text-ink/65">{{ __('Full-screen video area, bottom strip links, and arrow target.') }}</p>

            <div class="mt-6 grid gap-8 lg:grid-cols-12 lg:gap-10">
                <div class="space-y-5 lg:col-span-5">
                    <div>
                        <label class="{{ $label }}">{{ __('Headline') }}</label>
                        <input name="hero_headline" value="{{ old('hero_headline', $values['hero_headline'] ?? '') }}" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Subheadline / footer line') }}</label>
                        <textarea name="hero_subheadline" rows="2" class="{{ $field }}">{{ old('hero_subheadline', $values['hero_subheadline'] ?? '') }}</textarea>
                        <p class="{{ $hint }}">{{ __('Short line centered at the bottom of the hero (above the fold).') }}</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}">{{ __('Hero background') }}</label>
                            <select name="hero_mode" class="{{ $field }}">
                                <option value="single" @selected(old('hero_mode', $values['hero_mode'] ?? 'single') === 'single')>{{ __('Single video') }}</option>
                                <option value="carousel" @selected(old('hero_mode', $values['hero_mode'] ?? '') === 'carousel')>{{ __('Video carousel') }}</option>
                            </select>
                            <p class="{{ $hint }}">{{ __('Vimeo only for single; carousel needs 2+ MP4 lines.') }}</p>
                        </div>
                        <div>
                            <label class="{{ $label }}">{{ __('Video source') }}</label>
                            <select name="hero_video_source" class="{{ $field }}">
                                <option value="vimeo" @selected(old('hero_video_source', $values['hero_video_source'] ?? 'vimeo') === 'vimeo')>{{ __('Vimeo') }}</option>
                                <option value="mp4" @selected(old('hero_video_source', $values['hero_video_source'] ?? '') === 'mp4')>{{ __('Direct MP4') }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Single video URL') }}</label>
                        <input name="hero_video_url" type="url" value="{{ old('hero_video_url', $values['hero_video_url'] ?? '') }}" class="{{ $field }} font-mono" placeholder="https://…">
                        <p class="{{ $hint }}">{{ __('Fallback when carousel is empty or single mode.') }}</p>
                    </div>
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label class="{{ $label }}">{{ __('Carousel interval (sec)') }}</label>
                            <input name="hero_carousel_interval" type="number" min="3" max="120" value="{{ old('hero_carousel_interval', $values['hero_carousel_interval'] ?? '10') }}" class="{{ $field }} w-28">
                        </div>
                    </div>
                </div>

                <div class="space-y-5 lg:col-span-7">
                    <div>
                        <label class="{{ $label }}">{{ __('Carousel video URLs') }}</label>
                        <textarea name="hero_video_urls_text" rows="8" class="{{ $field }} min-h-[12rem] font-mono text-xs leading-relaxed" placeholder="https://…&#10;https://…">{{ old('hero_video_urls_text', $values['hero_video_urls_text'] ?? '') }}</textarea>
                        <p class="{{ $hint }}">{{ __('One MP4 URL per line. Two or more lines activate the carousel.') }}</p>
                    </div>
                    <p class="text-xs font-medium uppercase tracking-wide text-ink/50">{{ __('Bottom strip & arrow') }}</p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="{{ $label }}">{{ __('Link 1 label') }}</label><input name="hero_link_1_label" value="{{ old('hero_link_1_label', $values['hero_link_1_label'] ?? '') }}" class="{{ $field }}" placeholder="{{ __('Signature journeys') }}"></div>
                        <div><label class="{{ $label }}">{{ __('Link 1 URL') }}</label><input name="hero_link_1_url" type="url" value="{{ old('hero_link_1_url', $values['hero_link_1_url'] ?? '') }}" class="{{ $field }} font-mono"></div>
                        <div><label class="{{ $label }}">{{ __('Link 2 label') }}</label><input name="hero_link_2_label" value="{{ old('hero_link_2_label', $values['hero_link_2_label'] ?? '') }}" class="{{ $field }}"></div>
                        <div><label class="{{ $label }}">{{ __('Link 2 URL') }}</label><input name="hero_link_2_url" type="url" value="{{ old('hero_link_2_url', $values['hero_link_2_url'] ?? '') }}" class="{{ $field }} font-mono"></div>
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Large arrow icon URL') }}</label>
                        <input name="hero_icon_url" type="url" value="{{ old('hero_icon_url', $values['hero_icon_url'] ?? '') }}" class="{{ $field }} font-mono" placeholder="{{ __('Leave empty for Safari page') }}">
                        <p class="{{ $hint }}">{{ __('Optional.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Welcome --}}
        <section id="welcome-section" class="scroll-mt-6 space-y-6 rounded-2xl border-2 border-primary/25 bg-gradient-to-br from-primary/[0.06] via-white to-secondary/[0.1] p-6 sm:p-8">
            <div class="max-w-3xl">
                <h2 class="text-lg font-bold text-primary">{{ __('Welcome section') }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ __('Below the hero: headline, body, button, and two cards. Use | in the title for line breaks; blank line in body for paragraphs.') }}</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-12 lg:gap-8">
                <div class="space-y-5 lg:col-span-12 xl:col-span-5">
                    <div>
                        <label class="{{ $label }}">{{ __('Title') }}</label>
                        <input name="welcome_title" value="{{ old('welcome_title', $values['welcome_title'] ?? '') }}" class="{{ $field }}" maxlength="500">
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Body') }}</label>
                        <textarea name="welcome_body" rows="6" class="{{ $field }} min-h-[8rem]">{{ old('welcome_body', $values['welcome_body'] ?? '') }}</textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}">{{ __('Button label') }}</label>
                            <input name="welcome_learn_more_label" value="{{ old('welcome_learn_more_label', $values['welcome_learn_more_label'] ?? '') }}" class="{{ $field }}" placeholder="{{ __('Plan My Safari') }}">
                        </div>
                        <div>
                            <label class="{{ $label }}">{{ __('Button URL') }}</label>
                            <input name="welcome_learn_more_url" type="url" value="{{ old('welcome_learn_more_url', $values['welcome_learn_more_url'] ?? '') }}" class="{{ $field }} font-mono" placeholder="https://…">
                        </div>
                    </div>
                </div>

                <div class="space-y-6 border-t border-primary/15 pt-6 lg:col-span-12 xl:col-span-7 xl:border-l xl:border-t-0 xl:pl-8 xl:pt-0">
                    <div class="grid gap-8 lg:grid-cols-2">
                        {{-- Card 1 --}}
                        <div class="rounded-xl border border-secondary/50 bg-white/80 p-5 shadow-sm">
                            <p class="text-sm font-semibold text-ink">{{ __('Middle card') }}</p>
                            <p class="mt-1 text-xs text-ink/60">{{ __('Image upload, URL, or Vimeo.') }}</p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="{{ $label }}">{{ __('Media type') }}</label>
                                    <select name="welcome_card_1_media_type" class="{{ $field }}">
                                        <option value="image" @selected(old('welcome_card_1_media_type', $values['welcome_card_1_media_type'] ?? 'image') === 'image')>{{ __('Image') }}</option>
                                        <option value="vimeo" @selected(old('welcome_card_1_media_type', $values['welcome_card_1_media_type'] ?? '') === 'vimeo')>{{ __('Vimeo') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Image file') }}</label>
                                    <input name="welcome_card_1_image" type="file" accept="image/jpeg,image/png,image/gif,image/webp" class="{{ $field }} file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-primary">
                                    <p class="{{ $hint }}">{{ __('Max 8 MB. Replaces URL or previous upload.') }}</p>
                                    @php
                                        $w1Preview = \App\Models\SiteSetting::resolvePublicAssetUrl($values['welcome_card_1_image_url'] ?? '');
                                    @endphp
                                    @if($w1Preview)
                                        <div class="mt-3 flex flex-wrap items-center gap-3">
                                            <img src="{{ $w1Preview }}" alt="" class="max-h-36 max-w-full rounded-lg border border-secondary/40 object-contain">
                                            <label class="inline-flex items-center gap-2 text-xs text-ink/80">
                                                <input type="checkbox" name="remove_welcome_card_1_image" value="1" class="rounded border-secondary text-primary focus:ring-primary/40" @checked(old('remove_welcome_card_1_image'))>
                                                {{ __('Remove') }}
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Image URL') }}</label>
                                    <input name="welcome_card_1_image_url" type="url" value="{{ old('welcome_card_1_image_url', \App\Models\SiteSetting::isExternalUrl($values['welcome_card_1_image_url'] ?? '') ? $values['welcome_card_1_image_url'] : '') }}" class="{{ $field }} font-mono text-xs" placeholder="https://…">
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Vimeo URL') }}</label>
                                    <input name="welcome_card_1_vimeo_url" type="url" value="{{ old('welcome_card_1_vimeo_url', $values['welcome_card_1_vimeo_url'] ?? '') }}" class="{{ $field }} font-mono text-xs" placeholder="https://vimeo.com/…">
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Overlay text') }}</label>
                                    <input name="welcome_card_1_overlay" value="{{ old('welcome_card_1_overlay', $values['welcome_card_1_overlay'] ?? '') }}" class="{{ $field }}">
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Card link') }}</label>
                                    <input name="welcome_card_1_link" type="url" value="{{ old('welcome_card_1_link', $values['welcome_card_1_link'] ?? '') }}" class="{{ $field }} font-mono text-xs" placeholder="{{ __('Plan My Safari default') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Card 2 --}}
                        <div class="rounded-xl border border-secondary/50 bg-white/80 p-5 shadow-sm">
                            <p class="text-sm font-semibold text-ink">{{ __('Right card') }}</p>
                            <p class="mt-1 text-xs text-ink/60">{{ __('Upload, URL, Vimeo; optional stat.') }}</p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="{{ $label }}">{{ __('Media type') }}</label>
                                    <select name="welcome_card_2_media_type" class="{{ $field }}">
                                        <option value="image" @selected(old('welcome_card_2_media_type', $values['welcome_card_2_media_type'] ?? 'image') === 'image')>{{ __('Image') }}</option>
                                        <option value="vimeo" @selected(old('welcome_card_2_media_type', $values['welcome_card_2_media_type'] ?? '') === 'vimeo')>{{ __('Vimeo') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Image file') }}</label>
                                    <input name="welcome_card_2_image" type="file" accept="image/jpeg,image/png,image/gif,image/webp" class="{{ $field }} file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-primary">
                                    <p class="{{ $hint }}">{{ __('Max 8 MB.') }}</p>
                                    @php
                                        $w2Preview = \App\Models\SiteSetting::resolvePublicAssetUrl($values['welcome_card_2_image_url'] ?? '');
                                    @endphp
                                    @if($w2Preview)
                                        <div class="mt-3 flex flex-wrap items-center gap-3">
                                            <img src="{{ $w2Preview }}" alt="" class="max-h-36 max-w-full rounded-lg border border-secondary/40 object-contain">
                                            <label class="inline-flex items-center gap-2 text-xs text-ink/80">
                                                <input type="checkbox" name="remove_welcome_card_2_image" value="1" class="rounded border-secondary text-primary focus:ring-primary/40" @checked(old('remove_welcome_card_2_image'))>
                                                {{ __('Remove') }}
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Image URL') }}</label>
                                    <input name="welcome_card_2_image_url" type="url" value="{{ old('welcome_card_2_image_url', \App\Models\SiteSetting::isExternalUrl($values['welcome_card_2_image_url'] ?? '') ? $values['welcome_card_2_image_url'] : '') }}" class="{{ $field }} font-mono text-xs" placeholder="https://…">
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Vimeo URL') }}</label>
                                    <input name="welcome_card_2_vimeo_url" type="url" value="{{ old('welcome_card_2_vimeo_url', $values['welcome_card_2_vimeo_url'] ?? '') }}" class="{{ $field }} font-mono text-xs" placeholder="https://vimeo.com/…">
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="{{ $label }}">{{ __('Stat') }}</label>
                                        <input name="welcome_card_2_stat" value="{{ old('welcome_card_2_stat', $values['welcome_card_2_stat'] ?? '') }}" class="{{ $field }}" placeholder="15+">
                                    </div>
                                    <div>
                                        <label class="{{ $label }}">{{ __('Overlay') }}</label>
                                        <input name="welcome_card_2_overlay" value="{{ old('welcome_card_2_overlay', $values['welcome_card_2_overlay'] ?? '') }}" class="{{ $field }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="{{ $label }}">{{ __('Card link') }}</label>
                                    <input name="welcome_card_2_link" type="url" value="{{ old('welcome_card_2_link', $values['welcome_card_2_link'] ?? '') }}" class="{{ $field }} font-mono text-xs" placeholder="{{ __('Articles default') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Why choose us --}}
        <section id="section-why-choose" class="{{ $section }} scroll-mt-6">
            <h2 class="{{ $sectionTitle }}">{{ __('Why choose us') }}</h2>
            <p class="mt-1 max-w-3xl text-sm text-ink/65">{{ __('Headline, optional intro, and up to eight highlight cards (emoji + title + short text). Leave a card title empty to skip it.') }}</p>
            <div class="mt-6 space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="{{ $label }}">{{ __('Eyebrow label') }}</label>
                        <input name="why_choose_eyebrow" value="{{ old('why_choose_eyebrow', $values['why_choose_eyebrow'] ?? '') }}" class="{{ $field }}" placeholder="{{ __('Why us') }}" maxlength="80">
                        <p class="{{ $hint }}">{{ __('Small uppercase line above the main title.') }}</p>
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Section title') }}</label>
                        <input name="why_choose_title" value="{{ old('why_choose_title', $values['why_choose_title'] ?? '') }}" class="{{ $field }}" placeholder="{{ __('Why Choose Us') }}" maxlength="160">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="{{ $label }}">{{ __('Intro line (optional)') }}</label>
                        <textarea name="why_choose_subtitle" rows="2" class="{{ $field }}" maxlength="500" placeholder="{{ __('Short sentence under the heading') }}">{{ old('why_choose_subtitle', $values['why_choose_subtitle'] ?? '') }}</textarea>
                    </div>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink/50">{{ __('Cards') }}</p>
                <div class="space-y-4">
                    @foreach($values['why_choose_items'] ?? [] as $idx => $item)
                        <div class="rounded-xl border border-secondary/50 bg-surface/50 p-4">
                            <p class="mb-3 text-xs font-medium text-ink/60">{{ __('Card') }} {{ $idx + 1 }}</p>
                            <div class="grid gap-4 sm:grid-cols-12">
                                <div class="sm:col-span-2">
                                    <label class="{{ $label }}">{{ __('Icon') }}</label>
                                    <input name="why_choose_items[{{ $idx }}][icon]" value="{{ old('why_choose_items.'.$idx.'.icon', $item['icon'] ?? '') }}" class="{{ $field }} text-center text-lg" maxlength="32" placeholder="🌍" autocomplete="off">
                                    <p class="{{ $hint }}">{{ __('Emoji or short symbol') }}</p>
                                </div>
                                <div class="sm:col-span-10">
                                    <label class="{{ $label }}">{{ __('Title') }}</label>
                                    <input name="why_choose_items[{{ $idx }}][title]" value="{{ old('why_choose_items.'.$idx.'.title', $item['title'] ?? '') }}" class="{{ $field }}" maxlength="160">
                                </div>
                                <div class="sm:col-span-12 sm:col-start-3 lg:col-span-10">
                                    <label class="{{ $label }}">{{ __('Description') }}</label>
                                    <textarea name="why_choose_items[{{ $idx }}][body]" rows="2" class="{{ $field }}" maxlength="800">{{ old('why_choose_items.'.$idx.'.body', $item['body'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA + About side by side on xl --}}
        <div class="grid gap-8 xl:grid-cols-2">
            <section id="section-cta" class="{{ $section }} scroll-mt-6">
                <h2 class="{{ $sectionTitle }}">{{ __('Call to action') }}</h2>
                <p class="mt-1 text-sm text-ink/60">{{ __('Homepage CTA block copy.') }}</p>
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="{{ $label }}">{{ __('Title') }}</label>
                        <input name="cta_title" value="{{ old('cta_title', $values['cta_title'] ?? '') }}" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Body') }}</label>
                        <textarea name="cta_body" rows="3" class="{{ $field }}">{{ old('cta_body', $values['cta_body'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Button label') }}</label>
                        <input name="cta_button_label" value="{{ old('cta_button_label', $values['cta_button_label'] ?? '') }}" class="{{ $field }}" placeholder="{{ __('Plan My Safari') }}" maxlength="120">
                        <p class="{{ $hint }}">{{ __('Leave empty to use “Plan My Safari”.') }}</p>
                    </div>
                    <div>
                        <label class="{{ $label }}">{{ __('Button URL (optional)') }}</label>
                        <input name="cta_button_url" value="{{ old('cta_button_url', $values['cta_button_url'] ?? '') }}" class="{{ $field }} font-mono text-xs" placeholder="/plan-my-safari or https://…" maxlength="2000">
                        <p class="{{ $hint }}">{{ __('Full URL or path starting with /. Leave empty for the Plan My Safari form.') }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="sticky bottom-0 z-10 -mx-4 flex justify-end border-t border-secondary/50 bg-surface/95 px-4 py-4 backdrop-blur-sm lg:-mx-8 lg:px-8">
            <button type="submit" class="inline-flex min-w-[10rem] items-center justify-center rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                {{ __('Save homepage') }}
            </button>
        </div>
    </form>
</div>
@endsection
