<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutCoreValue;
use App\Models\AboutFleetImage;
use App\Models\AboutFleetSubsection;
use App\Models\AboutPageSetting;
use App\Models\AboutSafetyPoint;
use App\Models\AboutSustainabilityItem;
use App\Models\AboutTeamRole;
use App\Models\AboutVisionMissionCard;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function edit(): View
    {
        $setting = AboutPageSetting::query()->firstOrFail();

        return view('admin.about-page.edit', [
            'setting' => $setting,
            'visionCards' => AboutVisionMissionCard::query()->orderBy('sort_order')->get(),
            'coreValues' => AboutCoreValue::query()->orderBy('sort_order')->get(),
            'fleetImages' => AboutFleetImage::query()->orderBy('sort_order')->get(),
            'fleetSubsections' => AboutFleetSubsection::query()->orderBy('sort_order')->get(),
            'teamRoles' => AboutTeamRole::query()->orderBy('sort_order')->get(),
            'safetyPoints' => AboutSafetyPoint::query()->orderBy('sort_order')->get(),
            'sustainabilityItems' => AboutSustainabilityItem::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = AboutPageSetting::query()->firstOrFail();

        $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:2000'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
            'remove_hero_image' => ['nullable', 'boolean'],
            'intro_heading' => ['required', 'string', 'max:255'],
            'intro_paragraph_1' => ['required', 'string', 'max:12000'],
            'intro_paragraph_2' => ['nullable', 'string', 'max:12000'],
            'intro_image' => ['nullable', 'image', 'max:8192'],
            'remove_intro_image' => ['nullable', 'boolean'],
            'intro_cta_label' => ['nullable', 'string', 'max:120'],
            'fleet_heading' => ['required', 'string', 'max:255'],
            'fleet_body' => ['required', 'string', 'max:15000'],
            'team_heading' => ['required', 'string', 'max:255'],
            'team_body' => ['required', 'string', 'max:15000'],
            'team_image' => ['nullable', 'image', 'max:8192'],
            'remove_team_image' => ['nullable', 'boolean'],
            'safety_heading' => ['required', 'string', 'max:255'],
            'safety_body' => ['required', 'string', 'max:15000'],
            'safety_image' => ['nullable', 'image', 'max:8192'],
            'remove_safety_image' => ['nullable', 'boolean'],
            'core_values_section_title' => ['nullable', 'string', 'max:255'],
            'sustainability_section_title' => ['nullable', 'string', 'max:255'],
            'testimonials_section_title' => ['nullable', 'string', 'max:255'],
            'cta_heading' => ['required', 'string', 'max:255'],
            'cta_body' => ['required', 'string', 'max:4000'],
            'cta_button_label' => ['nullable', 'string', 'max:120'],
            'vision_cards' => ['nullable', 'array'],
            'vision_cards.*.id' => ['required', 'integer', 'exists:about_vision_mission_cards,id'],
            'vision_cards.*.title' => ['required', 'string', 'max:255'],
            'vision_cards.*.body' => ['required', 'string', 'max:2000'],
            'vision_cards.*.icon' => ['nullable', 'string', 'max:32'],
            'vision_cards.*.is_active' => ['nullable', 'boolean'],
            'core_values' => ['nullable', 'array'],
            'core_values.*.id' => ['required', 'integer', 'exists:about_core_values,id'],
            'core_values.*.title' => ['required', 'string', 'max:255'],
            'core_values.*.description' => ['nullable', 'string', 'max:2000'],
            'core_values.*.icon' => ['nullable', 'string', 'max:32'],
            'core_values.*.is_active' => ['nullable', 'boolean'],
            'fleet_subsections' => ['nullable', 'array'],
            'fleet_subsections.*.id' => ['required', 'integer', 'exists:about_fleet_subsections,id'],
            'fleet_subsections.*.title' => ['required', 'string', 'max:255'],
            'fleet_subsections.*.body' => ['nullable', 'string', 'max:4000'],
            'fleet_subsections.*.is_active' => ['nullable', 'boolean'],
            'fleet_images' => ['nullable', 'array'],
            'fleet_images.*.id' => ['required', 'integer', 'exists:about_fleet_images,id'],
            'fleet_images.*.caption' => ['nullable', 'string', 'max:255'],
            'fleet_images.*.image' => ['nullable', 'image', 'max:8192'],
            'fleet_images.*.remove_image' => ['nullable', 'boolean'],
            'fleet_images.*.is_active' => ['nullable', 'boolean'],
            'team_roles' => ['nullable', 'array'],
            'team_roles.*.id' => ['required', 'integer', 'exists:about_team_roles,id'],
            'team_roles.*.label' => ['required', 'string', 'max:255'],
            'team_roles.*.is_active' => ['nullable', 'boolean'],
            'safety_points' => ['nullable', 'array'],
            'safety_points.*.id' => ['required', 'integer', 'exists:about_safety_points,id'],
            'safety_points.*.point_text' => ['required', 'string', 'max:500'],
            'safety_points.*.is_active' => ['nullable', 'boolean'],
            'sustainability_items' => ['nullable', 'array'],
            'sustainability_items.*.id' => ['required', 'integer', 'exists:about_sustainability_items,id'],
            'sustainability_items.*.title' => ['required', 'string', 'max:255'],
            'sustainability_items.*.description' => ['nullable', 'string', 'max:2000'],
            'sustainability_items.*.icon' => ['nullable', 'string', 'max:32'],
            'sustainability_items.*.is_active' => ['nullable', 'boolean'],
        ]);

        $heroPath = $this->syncImageField(
            $request,
            'hero_image',
            'remove_hero_image',
            $setting->hero_image,
            'about'
        );
        $introPath = $this->syncImageField(
            $request,
            'intro_image',
            'remove_intro_image',
            $setting->intro_image,
            'about'
        );
        $teamPath = $this->syncImageField(
            $request,
            'team_image',
            'remove_team_image',
            $setting->team_image,
            'about'
        );
        $safetyPath = $this->syncImageField(
            $request,
            'safety_image',
            'remove_safety_image',
            $setting->safety_image,
            'about'
        );

        $setting->update([
            'hero_title' => $request->input('hero_title'),
            'hero_subtitle' => $request->input('hero_subtitle'),
            'hero_image' => $heroPath,
            'intro_heading' => $request->input('intro_heading'),
            'intro_paragraph_1' => $request->input('intro_paragraph_1'),
            'intro_paragraph_2' => $request->input('intro_paragraph_2'),
            'intro_image' => $introPath,
            'intro_cta_label' => $request->input('intro_cta_label'),
            'fleet_heading' => $request->input('fleet_heading'),
            'fleet_body' => $request->input('fleet_body'),
            'team_heading' => $request->input('team_heading'),
            'team_body' => $request->input('team_body'),
            'team_image' => $teamPath,
            'safety_heading' => $request->input('safety_heading'),
            'safety_body' => $request->input('safety_body'),
            'safety_image' => $safetyPath,
            'core_values_section_title' => $request->input('core_values_section_title', 'Our Core Values'),
            'sustainability_section_title' => $request->input('sustainability_section_title', 'Our Sustainability Initiatives'),
            'testimonials_section_title' => $request->input('testimonials_section_title', 'What Travelers Say'),
            'cta_heading' => $request->input('cta_heading'),
            'cta_body' => $request->input('cta_body'),
            'cta_button_label' => $request->input('cta_button_label'),
        ]);

        foreach ($request->input('vision_cards', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutVisionMissionCard::query()->where('id', (int) $row['id'])->update([
                'title' => $row['title'],
                'body' => $row['body'],
                'icon' => trim((string) ($row['icon'] ?? '')) ?: '🎯',
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('core_values', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutCoreValue::query()->where('id', (int) $row['id'])->update([
                'title' => $row['title'],
                'description' => $row['description'] ?? null,
                'icon' => trim((string) ($row['icon'] ?? '')) ?: '✓',
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('fleet_subsections', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutFleetSubsection::query()->where('id', (int) $row['id'])->update([
                'title' => $row['title'],
                'body' => $row['body'] ?? null,
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('fleet_images', []) as $i => $row) {
            if (! is_array($row)) {
                continue;
            }
            $img = AboutFleetImage::query()->find((int) $row['id']);
            if (! $img) {
                continue;
            }
            $path = $img->image;
            if (! empty($row['remove_image'])) {
                $this->deletePublicFile($path);
                $path = null;
            }
            /** @var UploadedFile|null $upload */
            $upload = $request->file("fleet_images.$i.image");
            if ($upload) {
                $this->deletePublicFile($path);
                $path = $upload->store('about/fleet', 'public');
            }
            $img->update([
                'caption' => $row['caption'] ?? null,
                'image' => $path,
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('team_roles', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutTeamRole::query()->where('id', (int) $row['id'])->update([
                'label' => $row['label'],
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('safety_points', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutSafetyPoint::query()->where('id', (int) $row['id'])->update([
                'point_text' => $row['point_text'],
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        foreach ($request->input('sustainability_items', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            AboutSustainabilityItem::query()->where('id', (int) $row['id'])->update([
                'title' => $row['title'],
                'description' => $row['description'] ?? null,
                'icon' => trim((string) ($row['icon'] ?? '')) ?: '🌿',
                'is_active' => ! empty($row['is_active']),
            ]);
        }

        ActivityLog::record('about_page.updated', 'About page content');

        return back()->with('success', __('About page saved.'));
    }

    private function syncImageField(Request $request, string $fileKey, string $removeKey, ?string $current, string $folder): ?string
    {
        if ($request->boolean($removeKey)) {
            $this->deletePublicFile($current);

            return null;
        }

        /** @var UploadedFile|null $upload */
        $upload = $request->file($fileKey);
        if ($upload) {
            $this->deletePublicFile($current);

            return $upload->store($folder, 'public');
        }

        return $current;
    }

    private function deletePublicFile(?string $path): void
    {
        if (! filled($path)) {
            return;
        }
        if (str_starts_with($path, 'http')) {
            return;
        }
        Storage::disk('public')->delete($path);
    }
}
