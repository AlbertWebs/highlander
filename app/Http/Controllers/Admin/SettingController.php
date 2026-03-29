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

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'site_hours' => SiteSetting::getValue('site_hours', ''),
            'contact_email' => SiteSetting::getValue('contact_email', ''),
            'contact_phone' => SiteSetting::getValue('contact_phone', ''),
            'contact_address' => SiteSetting::getValue('contact_address', ''),
            'contact_map_embed_url' => SiteSetting::getValue('contact_map_embed_url', ''),
            'social_facebook' => SiteSetting::getValue('social_facebook', ''),
            'social_instagram' => SiteSetting::getValue('social_instagram', ''),
            'social_twitter' => SiteSetting::getValue('social_twitter', ''),
            'social_youtube' => SiteSetting::getValue('social_youtube', ''),
            'social_tiktok' => SiteSetting::getValue('social_tiktok', ''),
            'site_logo' => SiteSetting::getValue('site_logo', ''),
            'site_logo_dark' => SiteSetting::getValue('site_logo_dark', ''),
            'site_favicon' => SiteSetting::getValue('site_favicon', ''),
            'menu_background_image' => SiteSetting::getValue('menu_background_image', ''),
            'footer_credits' => SiteSetting::getValue('footer_credits', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_hours' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:100'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'contact_map_embed_url' => ['nullable', 'string', 'max:2000', 'url'],
            'social_facebook' => ['nullable', 'url', 'max:500'],
            'social_instagram' => ['nullable', 'url', 'max:500'],
            'social_twitter' => ['nullable', 'url', 'max:500'],
            'social_youtube' => ['nullable', 'url', 'max:500'],
            'social_tiktok' => ['nullable', 'url', 'max:500'],
            'logo' => ['nullable', 'file', 'max:4096', 'mimes:jpeg,jpg,png,gif,webp,svg'],
            'logo_dark' => ['nullable', 'file', 'max:4096', 'mimes:jpeg,jpg,png,gif,webp,svg'],
            'favicon' => ['nullable', 'file', 'max:1024', 'mimes:ico,png,svg,gif,jpeg,jpg'],
            'menu_background' => ['nullable', 'file', 'max:5120', 'mimes:jpeg,jpg,png,gif,webp'],
            'footer_credits' => ['nullable', 'string', 'max:2000'],
        ]);

        unset($data['logo'], $data['logo_dark'], $data['favicon'], $data['menu_background']);

        foreach ($data as $key => $value) {
            SiteSetting::setValue($key, $value ?? '');
        }

        if ($request->hasFile('logo')) {
            $this->replaceStoredFile('site_logo', $request->file('logo'));
        } elseif ($request->boolean('remove_logo')) {
            $this->clearStoredFile('site_logo');
        }

        if ($request->hasFile('logo_dark')) {
            $this->replaceStoredFile('site_logo_dark', $request->file('logo_dark'));
        } elseif ($request->boolean('remove_logo_dark')) {
            $this->clearStoredFile('site_logo_dark');
        }

        if ($request->hasFile('favicon')) {
            $this->replaceStoredFile('site_favicon', $request->file('favicon'));
        } elseif ($request->boolean('remove_favicon')) {
            $this->clearStoredFile('site_favicon');
        }

        if ($request->hasFile('menu_background')) {
            $this->replaceStoredFile('menu_background_image', $request->file('menu_background'));
        } elseif ($request->boolean('remove_menu_background')) {
            $this->clearStoredFile('menu_background_image');
        }

        ActivityLog::record('settings.updated', 'Site settings');
        Cache::forget('home_page_v3');

        return back()->with('success', __('Settings saved.'));
    }

    private function replaceStoredFile(string $key, UploadedFile $file): void
    {
        $this->deleteFileIfExists(SiteSetting::getValue($key, ''));
        $path = $file->store('settings', 'public');
        SiteSetting::setValue($key, $path);
    }

    private function clearStoredFile(string $key): void
    {
        $this->deleteFileIfExists(SiteSetting::getValue($key, ''));
        SiteSetting::setValue($key, '');
    }

    private function deleteFileIfExists(mixed $path): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
