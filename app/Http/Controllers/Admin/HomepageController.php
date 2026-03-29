<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function edit(): View
    {
        $keys = [
            'hero_headline', 'hero_subheadline',
            'hero_link_1_label', 'hero_link_1_url', 'hero_link_2_label', 'hero_link_2_url', 'hero_icon_url',
            'hero_video_url', 'hero_video_source',
            'welcome_title', 'welcome_body',
            'welcome_learn_more_label', 'welcome_learn_more_url',
            'welcome_card_1_media_type', 'welcome_card_1_image_url', 'welcome_card_1_vimeo_url', 'welcome_card_1_overlay', 'welcome_card_1_link',
            'welcome_card_2_media_type', 'welcome_card_2_image_url', 'welcome_card_2_vimeo_url', 'welcome_card_2_stat', 'welcome_card_2_overlay', 'welcome_card_2_link',
            'cta_title', 'cta_body', 'cta_button_label', 'cta_button_url',
            'why_choose_eyebrow', 'why_choose_title', 'why_choose_subtitle',
        ];
        $values = [];
        foreach ($keys as $k) {
            $values[$k] = SiteSetting::getValue($k, '');
        }

        $rawWhyItems = SiteSetting::getValue('why_choose_items', []);
        $whyItems = is_array($rawWhyItems) ? array_values($rawWhyItems) : [];
        $whyItems = array_values(array_filter($whyItems, fn ($row) => is_array($row) && trim((string) ($row['title'] ?? '')) !== ''));
        while (count($whyItems) < 8) {
            $whyItems[] = ['icon' => '', 'title' => '', 'body' => ''];
        }
        $values['why_choose_items'] = array_slice($whyItems, 0, 8);

        $values['hero_mode'] = SiteSetting::getValue('hero_mode', 'single');
        $values['hero_video_source'] = SiteSetting::getValue('hero_video_source', 'vimeo');
        $values['hero_carousel_interval'] = SiteSetting::getValue('hero_carousel_interval', '10');

        $urls = SiteSetting::getValue('hero_video_urls', []);
        if (is_string($urls)) {
            $decoded = json_decode($urls, true);
            $urls = is_array($decoded) ? $decoded : [];
        }
        if (! is_array($urls)) {
            $urls = [];
        }
        $urls = array_values(array_filter($urls, fn ($u) => is_string($u) && filter_var($u, FILTER_VALIDATE_URL)));
        $values['hero_video_urls_text'] = implode("\n", $urls);

        return view('admin.homepage.edit', compact('values'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_subheadline' => ['nullable', 'string', 'max:500'],
            'hero_link_1_label' => ['nullable', 'string', 'max:120'],
            'hero_link_1_url' => ['nullable', 'url', 'max:2000'],
            'hero_link_2_label' => ['nullable', 'string', 'max:120'],
            'hero_link_2_url' => ['nullable', 'url', 'max:2000'],
            'hero_icon_url' => ['nullable', 'url', 'max:2000'],
            'hero_video_url' => ['nullable', 'url', 'max:2000'],
            'hero_video_source' => ['required', 'in:mp4,vimeo'],
            'hero_mode' => ['required', 'in:single,carousel'],
            'hero_carousel_interval' => ['nullable', 'integer', 'min:3', 'max:120'],
            'hero_video_urls_text' => ['nullable', 'string', 'max:20000'],
            'welcome_title' => ['nullable', 'string', 'max:500'],
            'welcome_body' => ['nullable', 'string', 'max:5000'],
            'welcome_learn_more_label' => ['nullable', 'string', 'max:120'],
            'welcome_learn_more_url' => ['nullable', 'string', 'max:2000'],
            'welcome_card_1_media_type' => ['nullable', 'in:image,vimeo'],
            'welcome_card_1_image' => ['nullable', 'file', 'max:8192', 'mimes:jpeg,jpg,png,gif,webp'],
            'welcome_card_1_image_url' => ['nullable', 'string', 'max:2000', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value === null || $value === '') {
                    return;
                }
                if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_URL)) {
                    $fail(__('The image URL must be a valid URL.'));
                }
            }],
            'remove_welcome_card_1_image' => ['nullable', 'boolean'],
            'welcome_card_1_vimeo_url' => ['nullable', 'string', 'max:2000'],
            'welcome_card_1_overlay' => ['nullable', 'string', 'max:500'],
            'welcome_card_1_link' => ['nullable', 'string', 'max:2000'],
            'welcome_card_2_media_type' => ['nullable', 'in:image,vimeo'],
            'welcome_card_2_image' => ['nullable', 'file', 'max:8192', 'mimes:jpeg,jpg,png,gif,webp'],
            'welcome_card_2_image_url' => ['nullable', 'string', 'max:2000', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value === null || $value === '') {
                    return;
                }
                if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_URL)) {
                    $fail(__('The image URL must be a valid URL.'));
                }
            }],
            'remove_welcome_card_2_image' => ['nullable', 'boolean'],
            'welcome_card_2_vimeo_url' => ['nullable', 'string', 'max:2000'],
            'welcome_card_2_stat' => ['nullable', 'string', 'max:40'],
            'welcome_card_2_overlay' => ['nullable', 'string', 'max:500'],
            'welcome_card_2_link' => ['nullable', 'string', 'max:2000'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_body' => ['nullable', 'string', 'max:2000'],
            'cta_button_label' => ['nullable', 'string', 'max:120'],
            'cta_button_url' => ['nullable', 'string', 'max:2000'],
            'why_choose_eyebrow' => ['nullable', 'string', 'max:80'],
            'why_choose_title' => ['nullable', 'string', 'max:160'],
            'why_choose_subtitle' => ['nullable', 'string', 'max:500'],
            'why_choose_items' => ['nullable', 'array', 'max:8'],
            'why_choose_items.*.icon' => ['nullable', 'string', 'max:32'],
            'why_choose_items.*.title' => ['nullable', 'string', 'max:160'],
            'why_choose_items.*.body' => ['nullable', 'string', 'max:800'],
        ]);

        $carouselUrls = collect(preg_split('/\r\n|\r|\n/', (string) ($data['hero_video_urls_text'] ?? '')))
            ->map(fn ($l) => trim((string) $l))
            ->filter(fn ($l) => $l !== '' && filter_var($l, FILTER_VALIDATE_URL))
            ->values()
            ->all();

        unset($data['hero_video_urls_text']);
        SiteSetting::setValue('hero_video_urls', json_encode($carouselUrls));

        $this->syncWelcomeCardImage($request, 'welcome_card_1_image', 'remove_welcome_card_1_image', 'welcome_card_1_image_url');
        $this->syncWelcomeCardImage($request, 'welcome_card_2_image', 'remove_welcome_card_2_image', 'welcome_card_2_image_url');

        unset(
            $data['welcome_card_1_image_url'],
            $data['welcome_card_2_image_url'],
            $data['remove_welcome_card_1_image'],
            $data['remove_welcome_card_2_image'],
            $data['welcome_card_1_image'],
            $data['welcome_card_2_image'],
        );

        $whyItems = collect($data['why_choose_items'] ?? [])
            ->filter(fn ($row) => is_array($row) && trim((string) ($row['title'] ?? '')) !== '')
            ->take(8)
            ->map(function (array $row): array {
                $icon = trim((string) ($row['icon'] ?? ''));
                if (mb_strlen($icon) > 32) {
                    $icon = mb_substr($icon, 0, 32);
                }

                return [
                    'icon' => $icon,
                    'title' => trim((string) ($row['title'] ?? '')),
                    'body' => trim((string) ($row['body'] ?? '')),
                ];
            })
            ->values()
            ->all();
        SiteSetting::setValue('why_choose_items', $whyItems);
        unset($data['why_choose_items']);

        $interval = max(3, min(120, (int) ($data['hero_carousel_interval'] ?? 10)));

        foreach ($data as $key => $value) {
            if ($value === null && $key !== 'hero_carousel_interval') {
                continue;
            }
            if ($key === 'hero_carousel_interval') {
                SiteSetting::setValue('hero_carousel_interval', (string) $interval);

                continue;
            }
            SiteSetting::setValue($key, is_string($value) ? $value : (string) $value);
        }

        ActivityLog::record('homepage.updated', 'Homepage content');
        Cache::forget('home_page_v3');
        Cache::forget('home_page_v4');

        return back()->with('success', __('Homepage content saved.'));
    }

    private function syncWelcomeCardImage(Request $request, string $fileKey, string $removeKey, string $settingKey): void
    {
        $existing = SiteSetting::getValue($settingKey, '');

        if ($request->boolean($removeKey)) {
            $this->deleteWelcomeStoredFile($existing);
            SiteSetting::setValue($settingKey, '');

            return;
        }

        /** @var UploadedFile|null $upload */
        $upload = $request->file($fileKey);
        if ($upload instanceof UploadedFile) {
            $this->deleteWelcomeStoredFile($existing);
            $path = $upload->store('welcome', 'public');
            SiteSetting::setValue($settingKey, $path);

            return;
        }

        $urlInput = trim((string) $request->input($settingKey, ''));
        if ($urlInput !== '') {
            if ($this->isWelcomeStoredPath($existing)) {
                $this->deleteWelcomeStoredFile($existing);
            }
            SiteSetting::setValue($settingKey, $urlInput);

            return;
        }

        if (is_string($existing) && $existing !== '' && SiteSetting::isExternalUrl($existing)) {
            SiteSetting::setValue($settingKey, '');
        }
    }

    private function isWelcomeStoredPath(mixed $value): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }
        if (SiteSetting::isExternalUrl($value)) {
            return false;
        }

        return Storage::disk('public')->exists($value);
    }

    private function deleteWelcomeStoredFile(mixed $path): void
    {
        if (! is_string($path) || $path === '' || SiteSetting::isExternalUrl($path)) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
