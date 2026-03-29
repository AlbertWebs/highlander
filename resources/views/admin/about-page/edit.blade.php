@extends('layouts.admin')
@section('title', __('About page'))
@section('heading', __('About page'))
@section('breadcrumb')<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a> / {{ __('About page') }}@endsection

@php
    $field = 'mt-1 w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 text-sm shadow-sm';
    $label = 'text-sm font-medium text-ink';
    $section = 'rounded-2xl border border-secondary/50 bg-white p-6 shadow-soft sm:p-8';
    $cb = 'rounded border-secondary/60 text-primary';
@endphp

@section('content')
<div class="mx-auto max-w-5xl space-y-6 pb-24">
    @if(session('success'))
        <div class="rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.about-page.update') }}" method="post" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Hero banner') }}</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">{{ __('Title') }}</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $setting->hero_title) }}" required class="{{ $field }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">{{ __('Subtitle') }}</label>
                    <textarea name="hero_subtitle" rows="2" class="{{ $field }}">{{ old('hero_subtitle', $setting->hero_subtitle) }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">{{ __('Background image') }}</label>
                    <input type="file" name="hero_image" accept="image/*" class="{{ $field }}">
                    @if($setting->hero_image)
                        <label class="mt-2 flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remove_hero_image" value="1" class="{{ $cb }}"> {{ __('Remove current image') }}</label>
                    @endif
                </div>
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Company introduction') }}</h2>
            <div class="mt-5 space-y-4">
                <div>
                    <label class="{{ $label }}">{{ __('Heading') }}</label>
                    <input type="text" name="intro_heading" value="{{ old('intro_heading', $setting->intro_heading) }}" required class="{{ $field }}">
                </div>
                <div>
                    <label class="{{ $label }}">{{ __('Paragraph 1') }}</label>
                    <textarea name="intro_paragraph_1" rows="4" required class="{{ $field }}">{{ old('intro_paragraph_1', $setting->intro_paragraph_1) }}</textarea>
                </div>
                <div>
                    <label class="{{ $label }}">{{ __('Paragraph 2') }}</label>
                    <textarea name="intro_paragraph_2" rows="3" class="{{ $field }}">{{ old('intro_paragraph_2', $setting->intro_paragraph_2) }}</textarea>
                </div>
                <div>
                    <label class="{{ $label }}">{{ __('CTA button label') }}</label>
                    <input type="text" name="intro_cta_label" value="{{ old('intro_cta_label', $setting->intro_cta_label) }}" class="{{ $field }}">
                </div>
                <div>
                    <label class="{{ $label }}">{{ __('Right column image') }}</label>
                    <input type="file" name="intro_image" accept="image/*" class="{{ $field }}">
                    @if($setting->intro_image)
                        <label class="mt-2 flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remove_intro_image" value="1" class="{{ $cb }}"> {{ __('Remove current image') }}</label>
                    @endif
                </div>
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Vision, mission & promise') }}</h2>
            <div class="mt-5 space-y-6">
                @foreach($visionCards as $i => $card)
                    <fieldset class="rounded-xl border border-secondary/40 p-4">
                        <input type="hidden" name="vision_cards[{{ $i }}][id]" value="{{ $card->id }}">
                        <input type="hidden" name="vision_cards[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="vision_cards[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('vision_cards.'.$i.'.is_active', $card->is_active))> {{ __('Active') }}</label>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="{{ $label }}">{{ __('Icon (emoji)') }}</label>
                                <input type="text" name="vision_cards[{{ $i }}][icon]" value="{{ old('vision_cards.'.$i.'.icon', $card->icon) }}" class="{{ $field }}" maxlength="32">
                            </div>
                            <div>
                                <label class="{{ $label }}">{{ __('Title') }}</label>
                                <input type="text" name="vision_cards[{{ $i }}][title]" value="{{ old('vision_cards.'.$i.'.title', $card->title) }}" required class="{{ $field }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="{{ $label }}">{{ __('Text') }}</label>
                                <textarea name="vision_cards[{{ $i }}][body]" rows="3" required class="{{ $field }}">{{ old('vision_cards.'.$i.'.body', $card->body) }}</textarea>
                            </div>
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Section titles') }}</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">{{ __('Core values title') }}</label>
                    <input type="text" name="core_values_section_title" value="{{ old('core_values_section_title', $setting->core_values_section_title) }}" class="{{ $field }}">
                </div>
                <div>
                    <label class="{{ $label }}">{{ __('Sustainability title') }}</label>
                    <input type="text" name="sustainability_section_title" value="{{ old('sustainability_section_title', $setting->sustainability_section_title) }}" class="{{ $field }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">{{ __('Testimonials title') }}</label>
                    <input type="text" name="testimonials_section_title" value="{{ old('testimonials_section_title', $setting->testimonials_section_title) }}" class="{{ $field }}">
                </div>
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Core values') }}</h2>
            <div class="mt-5 space-y-4">
                @foreach($coreValues as $i => $row)
                    <fieldset class="rounded-xl border border-secondary/40 p-4">
                        <input type="hidden" name="core_values[{{ $i }}][id]" value="{{ $row->id }}">
                        <input type="hidden" name="core_values[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="core_values[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('core_values.'.$i.'.is_active', $row->is_active))> {{ __('Active') }}</label>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div><label class="{{ $label }}">{{ __('Icon') }}</label><input type="text" name="core_values[{{ $i }}][icon]" value="{{ old('core_values.'.$i.'.icon', $row->icon) }}" class="{{ $field }}" maxlength="32"></div>
                            <div><label class="{{ $label }}">{{ __('Title') }}</label><input type="text" name="core_values[{{ $i }}][title]" value="{{ old('core_values.'.$i.'.title', $row->title) }}" required class="{{ $field }}"></div>
                            <div class="sm:col-span-2"><label class="{{ $label }}">{{ __('Description') }}</label><textarea name="core_values[{{ $i }}][description]" rows="2" class="{{ $field }}">{{ old('core_values.'.$i.'.description', $row->description) }}</textarea></div>
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Fleet & equipment') }}</h2>
            <div class="mt-5 space-y-4">
                <div><label class="{{ $label }}">{{ __('Heading') }}</label><input type="text" name="fleet_heading" value="{{ old('fleet_heading', $setting->fleet_heading) }}" required class="{{ $field }}"></div>
                <div><label class="{{ $label }}">{{ __('Introduction') }}</label><textarea name="fleet_body" rows="4" required class="{{ $field }}">{{ old('fleet_body', $setting->fleet_body) }}</textarea></div>
                <p class="text-sm font-medium text-ink">{{ __('Image grid (3 slots)') }}</p>
                @foreach($fleetImages as $i => $fi)
                    <fieldset class="rounded-xl border border-secondary/40 p-4">
                        <input type="hidden" name="fleet_images[{{ $i }}][id]" value="{{ $fi->id }}">
                        <input type="hidden" name="fleet_images[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="fleet_images[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('fleet_images.'.$i.'.is_active', $fi->is_active))> {{ __('Active') }}</label>
                        <div class="mt-3 space-y-3">
                            <div><label class="{{ $label }}">{{ __('Caption / placeholder label') }}</label><input type="text" name="fleet_images[{{ $i }}][caption]" value="{{ old('fleet_images.'.$i.'.caption', $fi->caption) }}" class="{{ $field }}"></div>
                            <div><label class="{{ $label }}">{{ __('Image') }}</label><input type="file" name="fleet_images[{{ $i }}][image]" accept="image/*" class="{{ $field }}"></div>
                            @if($fi->image)
                                <label class="flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="fleet_images[{{ $i }}][remove_image]" value="1" class="{{ $cb }}"> {{ __('Remove image') }}</label>
                            @endif
                        </div>
                    </fieldset>
                @endforeach
                <p class="text-sm font-medium text-ink">{{ __('Subsections') }}</p>
                @foreach($fleetSubsections as $i => $fs)
                    <fieldset class="rounded-xl border border-secondary/40 p-4">
                        <input type="hidden" name="fleet_subsections[{{ $i }}][id]" value="{{ $fs->id }}">
                        <input type="hidden" name="fleet_subsections[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="fleet_subsections[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('fleet_subsections.'.$i.'.is_active', $fs->is_active))> {{ __('Active') }}</label>
                        <div class="mt-3 space-y-3">
                            <div><label class="{{ $label }}">{{ __('Title') }}</label><input type="text" name="fleet_subsections[{{ $i }}][title]" value="{{ old('fleet_subsections.'.$i.'.title', $fs->title) }}" required class="{{ $field }}"></div>
                            <div><label class="{{ $label }}">{{ __('Body') }}</label><textarea name="fleet_subsections[{{ $i }}][body]" rows="2" class="{{ $field }}">{{ old('fleet_subsections.'.$i.'.body', $fs->body) }}</textarea></div>
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Our team') }}</h2>
            <div class="mt-5 space-y-4">
                <div><label class="{{ $label }}">{{ __('Heading') }}</label><input type="text" name="team_heading" value="{{ old('team_heading', $setting->team_heading) }}" required class="{{ $field }}"></div>
                <div><label class="{{ $label }}">{{ __('Introduction') }}</label><textarea name="team_body" rows="4" required class="{{ $field }}">{{ old('team_body', $setting->team_body) }}</textarea></div>
                <div>
                    <label class="{{ $label }}">{{ __('Team image') }}</label>
                    <input type="file" name="team_image" accept="image/*" class="{{ $field }}">
                    @if($setting->team_image)
                        <label class="mt-2 flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remove_team_image" value="1" class="{{ $cb }}"> {{ __('Remove current image') }}</label>
                    @endif
                </div>
                <p class="text-sm font-medium text-ink">{{ __('Roles list') }}</p>
                @foreach($teamRoles as $i => $tr)
                    <div class="flex flex-wrap items-end gap-3 rounded-xl border border-secondary/40 p-3">
                        <input type="hidden" name="team_roles[{{ $i }}][id]" value="{{ $tr->id }}">
                        <input type="hidden" name="team_roles[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="team_roles[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('team_roles.'.$i.'.is_active', $tr->is_active))> {{ __('Active') }}</label>
                        <div class="min-w-[12rem] flex-1"><label class="{{ $label }}">{{ __('Label') }}</label><input type="text" name="team_roles[{{ $i }}][label]" value="{{ old('team_roles.'.$i.'.label', $tr->label) }}" required class="{{ $field }}"></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Safety & compliance') }}</h2>
            <div class="mt-5 space-y-4">
                <div><label class="{{ $label }}">{{ __('Heading') }}</label><input type="text" name="safety_heading" value="{{ old('safety_heading', $setting->safety_heading) }}" required class="{{ $field }}"></div>
                <div><label class="{{ $label }}">{{ __('Introduction') }}</label><textarea name="safety_body" rows="3" required class="{{ $field }}">{{ old('safety_body', $setting->safety_body) }}</textarea></div>
                <div>
                    <label class="{{ $label }}">{{ __('Side image') }}</label>
                    <input type="file" name="safety_image" accept="image/*" class="{{ $field }}">
                    @if($setting->safety_image)
                        <label class="mt-2 flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remove_safety_image" value="1" class="{{ $cb }}"> {{ __('Remove current image') }}</label>
                    @endif
                </div>
                <p class="text-sm font-medium text-ink">{{ __('Bullet points') }}</p>
                @foreach($safetyPoints as $i => $sp)
                    <div class="flex flex-wrap items-end gap-3 rounded-xl border border-secondary/40 p-3">
                        <input type="hidden" name="safety_points[{{ $i }}][id]" value="{{ $sp->id }}">
                        <input type="hidden" name="safety_points[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="safety_points[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('safety_points.'.$i.'.is_active', $sp->is_active))> {{ __('Active') }}</label>
                        <div class="min-w-0 flex-1"><input type="text" name="safety_points[{{ $i }}][point_text]" value="{{ old('safety_points.'.$i.'.point_text', $sp->point_text) }}" required class="{{ $field }}"></div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Sustainability initiatives') }}</h2>
            <div class="mt-5 space-y-4">
                @foreach($sustainabilityItems as $i => $si)
                    <fieldset class="rounded-xl border border-secondary/40 p-4">
                        <input type="hidden" name="sustainability_items[{{ $i }}][id]" value="{{ $si->id }}">
                        <input type="hidden" name="sustainability_items[{{ $i }}][is_active]" value="0">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="sustainability_items[{{ $i }}][is_active]" value="1" class="{{ $cb }}" @checked(old('sustainability_items.'.$i.'.is_active', $si->is_active))> {{ __('Active') }}</label>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div><label class="{{ $label }}">{{ __('Icon') }}</label><input type="text" name="sustainability_items[{{ $i }}][icon]" value="{{ old('sustainability_items.'.$i.'.icon', $si->icon) }}" class="{{ $field }}" maxlength="32"></div>
                            <div><label class="{{ $label }}">{{ __('Title') }}</label><input type="text" name="sustainability_items[{{ $i }}][title]" value="{{ old('sustainability_items.'.$i.'.title', $si->title) }}" required class="{{ $field }}"></div>
                            <div class="sm:col-span-2"><label class="{{ $label }}">{{ __('Description') }}</label><textarea name="sustainability_items[{{ $i }}][description]" rows="2" class="{{ $field }}">{{ old('sustainability_items.'.$i.'.description', $si->description) }}</textarea></div>
                        </div>
                    </fieldset>
                @endforeach
            </div>
        </section>

        <section class="{{ $section }}">
            <h2 class="text-base font-semibold text-ink">{{ __('Closing call to action') }}</h2>
            <div class="mt-5 space-y-4">
                <div><label class="{{ $label }}">{{ __('Heading') }}</label><input type="text" name="cta_heading" value="{{ old('cta_heading', $setting->cta_heading) }}" required class="{{ $field }}"></div>
                <div><label class="{{ $label }}">{{ __('Text') }}</label><textarea name="cta_body" rows="3" required class="{{ $field }}">{{ old('cta_body', $setting->cta_body) }}</textarea></div>
                <div><label class="{{ $label }}">{{ __('Button label') }}</label><input type="text" name="cta_button_label" value="{{ old('cta_button_label', $setting->cta_button_label) }}" class="{{ $field }}"></div>
            </div>
            <p class="mt-4 text-sm text-ink/60">{{ __('Testimonials on this page are managed under Testimonials — enable “Show on About page” on each review.') }}</p>
        </section>

        <div class="sticky bottom-0 z-10 flex justify-end border-t border-secondary/50 bg-surface/95 py-4 backdrop-blur-sm">
            <button type="submit" class="inline-flex min-w-[10rem] items-center justify-center rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-soft transition hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                {{ __('Save About page') }}
            </button>
        </div>
    </form>
</div>
@endsection
