<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Destination;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\SafariExperience;
use App\Models\Tour;
use App\Support\Vimeo;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $data = Cache::remember('home_page_v13', 600, function () {
            $defaultMp4 = 'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4';
            $defaultVimeoPage = 'https://vimeo.com/1177988644';
            $heroVideoSource = SiteSetting::getValue('hero_video_source', 'vimeo');
            $heroMode = SiteSetting::getValue('hero_mode', 'single');
            $singleUrl = SiteSetting::getValue(
                'hero_video_url',
                $heroVideoSource === 'vimeo' ? $defaultVimeoPage : $defaultMp4
            );
            $carouselUrls = self::parseHeroVideoUrls(SiteSetting::getValue('hero_video_urls', ''));
            if ($heroMode === 'carousel' && count($carouselUrls) > 0) {
                $heroVideos = $carouselUrls;
            } else {
                $heroVideos = [$singleUrl];
            }
            $heroUseCarousel = $heroMode === 'carousel' && count($heroVideos) >= 2;
            $carouselInterval = max(3, min(120, (int) SiteSetting::getValue('hero_carousel_interval', 10)));

            $heroVimeoId = null;
            $heroUsesVimeoEmbed = false;
            if (! $heroUseCarousel && $heroVideoSource === 'vimeo') {
                $heroVimeoId = self::vimeoIdFromUrl($singleUrl);
                if ($heroVimeoId !== null) {
                    $heroUsesVimeoEmbed = true;
                } else {
                    $singleUrl = $defaultMp4;
                    $heroVideos = [$singleUrl];
                }
            }

            return [
                'hero_headline' => SiteSetting::getValue('hero_headline', 'Discover Africa Beyond the Ordinary Safari Experiences'),
                'hero_subheadline' => SiteSetting::getValue('hero_subheadline', 'Luxury safaris, mountain adventures, and unforgettable journeys.'),
                'hero_link_1_label' => SiteSetting::getValue('hero_link_1_label', __('Signature journeys')),
                'hero_link_1_url' => SiteSetting::getValue('hero_link_1_url', ''),
                'hero_link_2_label' => SiteSetting::getValue('hero_link_2_label', __('Mountain expeditions')),
                'hero_link_2_url' => SiteSetting::getValue('hero_link_2_url', ''),
                'hero_icon_url' => SiteSetting::getValue('hero_icon_url', ''),
                'hero_video_url' => $singleUrl,
                'hero_videos' => $heroVideos,
                'hero_use_carousel' => $heroUseCarousel,
                'hero_carousel_interval' => $carouselInterval,
                'hero_video_source' => $heroVideoSource,
                'hero_vimeo_id' => $heroVimeoId,
                'hero_uses_vimeo_embed' => $heroUsesVimeoEmbed,
                'welcome_title' => SiteSetting::getValue('welcome_title', 'Discover Africa Beyond the Ordinary—|Bespoke safaris.|Unforgettable journeys.'),
                'welcome_body' => SiteSetting::getValue('welcome_body', "We design private safaris and nature expeditions across East Africa—from open savanna to highland trails—tailored to your dates, pace, and the wildlife you hope to see.\n\nOur guides, vehicles, and partner lodges are chosen for safety, comfort, and respect for the wild, so you travel with confidence and return with memories that last."),
                'welcome_learn_more_label' => SiteSetting::getValue('welcome_learn_more_label', __('Plan My Safari')),
                'welcome_learn_more_url' => SiteSetting::getValue('welcome_learn_more_url', ''),
                'welcome_card_1_media_type' => SiteSetting::getValue('welcome_card_1_media_type', 'image'),
                'welcome_card_1_image_url' => self::resolveWelcomeCardImage(
                    SiteSetting::getValue('welcome_card_1_image_url', ''),
                    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&h=1200&q=80'
                ),
                'welcome_card_1_vimeo_url' => SiteSetting::getValue('welcome_card_1_vimeo_url', ''),
                'welcome_card_1_overlay' => SiteSetting::getValue('welcome_card_1_overlay', 'The wild opens up when your itinerary is built around you.'),
                'welcome_card_1_link' => SiteSetting::getValue('welcome_card_1_link', ''),
                'welcome_card_2_media_type' => SiteSetting::getValue('welcome_card_2_media_type', 'image'),
                'welcome_card_2_image_url' => self::resolveWelcomeCardImage(
                    SiteSetting::getValue('welcome_card_2_image_url', ''),
                    'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?auto=format&fit=crop&w=900&h=1200&q=80'
                ),
                'welcome_card_2_vimeo_url' => SiteSetting::getValue('welcome_card_2_vimeo_url', ''),
                'welcome_card_2_stat' => SiteSetting::getValue('welcome_card_2_stat', '15+'),
                'welcome_card_2_overlay' => SiteSetting::getValue('welcome_card_2_overlay', 'The best way to know Africa is on safari—with every day shaped for you.'),
                'welcome_card_2_link' => SiteSetting::getValue('welcome_card_2_link', ''),
                'cta_title' => SiteSetting::getValue('cta_title', 'Ready for your next adventure?'),
                'cta_body' => SiteSetting::getValue('cta_body', 'Speak with our travel designers and build an itinerary tailored to you.'),
                'cta_button_label' => SiteSetting::getValue('cta_button_label', ''),
                'cta_button_url' => SiteSetting::getValue('cta_button_url', ''),
                'why_choose_eyebrow' => self::sanitizeWhyChooseText(SiteSetting::getValue('why_choose_eyebrow', __('The Highlanders difference'))),
                'why_choose_title' => self::sanitizeWhyChooseText(SiteSetting::getValue('why_choose_title', __('Why travellers choose us'))),
                'why_choose_subtitle' => self::sanitizeWhyChooseText(SiteSetting::getValue('why_choose_subtitle', __('We design private safaris and treks across Kenya, Tanzania, and Uganda around your dates, pace, and interests. One team from first enquiry to the last night in camp.'))),
                'why_choose_items' => self::normalizeWhyChooseItems(SiteSetting::getValue('why_choose_items', null)),
                'social_facebook' => SiteSetting::getValue('social_facebook', ''),
                'social_instagram' => SiteSetting::getValue('social_instagram', ''),
                'social_youtube' => SiteSetting::getValue('social_youtube', ''),
                'social_twitter' => SiteSetting::getValue('social_twitter', ''),
                'social_tiktok' => SiteSetting::getValue('social_tiktok', ''),
                'popular_tours_title' => SiteSetting::getValue('popular_tours_title', __('Most Popular Tours')),
                'popular_tours_subtitle' => SiteSetting::getValue(
                    'popular_tours_subtitle',
                    __('Embark on an unforgettable safari adventure exploring the majestic peaks of Mount Kenya and Mount Kilimanjaro, while encountering iconic wildlife across East Africa.')
                ),
                'popular_tours' => Tour::popularForHomepage(8),
                'featured_safaris_by_country' => SafariExperience::featuredForHomepageByCountry(),
                'destinations' => Destination::query()->active()->orderByDesc('sort_order')->take(4)->get(),
                'testimonials' => Testimonial::query()->active()->orderByDesc('is_featured')->orderBy('sort_order')->take(6)->get(),
                'articles' => Article::query()->published()->orderByDesc('published_at')->take(3)->get(),
            ];
        });

        $data['hero_link_1_url'] = self::resolveHeroUrl($data['hero_link_1_url'] ?? '', 'safari');
        $data['hero_link_2_url'] = self::resolveHeroUrl($data['hero_link_2_url'] ?? '', 'mountains');
        $data['hero_icon_url'] = self::resolveHeroUrl($data['hero_icon_url'] ?? '', 'safari');

        $data['welcome_card_1_vimeo_id'] = ($data['welcome_card_1_media_type'] ?? 'image') === 'vimeo'
            ? Vimeo::idFromUrl(trim((string) ($data['welcome_card_1_vimeo_url'] ?? '')))
            : null;
        $data['welcome_card_2_vimeo_id'] = ($data['welcome_card_2_media_type'] ?? 'image') === 'vimeo'
            ? Vimeo::idFromUrl(trim((string) ($data['welcome_card_2_vimeo_url'] ?? '')))
            : null;

        $data['cta_button_href'] = self::resolveCtaButtonHref($data['cta_button_url'] ?? '');
        $data['cta_button_text'] = filled(trim((string) ($data['cta_button_label'] ?? '')))
            ? trim((string) $data['cta_button_label'])
            : __('Plan My Safari');

        return view('home', array_merge($data, SeoMeta::metaFor('home')));
    }

    protected static function resolveWelcomeCardImage(mixed $raw, string $defaultUrl): string
    {
        $resolved = SiteSetting::resolvePublicAssetUrl(trim((string) $raw));

        return $resolved ?? $defaultUrl;
    }

    protected static function resolveHeroUrl(mixed $url, string $defaultRoute): string
    {
        if (is_string($url) && $url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return route($defaultRoute);
    }

    protected static function resolveCtaButtonHref(mixed $raw): string
    {
        $u = trim((string) $raw);
        if ($u === '') {
            return route('plan-my-safari');
        }
        if (filter_var($u, FILTER_VALIDATE_URL)) {
            return $u;
        }
        if (str_starts_with($u, '/')) {
            return url($u);
        }

        return route('plan-my-safari');
    }

    /**
     * @return list<string>
     */
    protected static function parseHeroVideoUrls(mixed $raw): array
    {
        if (is_array($raw)) {
            $list = $raw;
        } elseif (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $list = is_array($decoded) ? $decoded : [];
        } else {
            $list = [];
        }

        $out = [];
        foreach ($list as $u) {
            if (is_string($u) && filter_var($u, FILTER_VALIDATE_URL)) {
                $out[] = $u;
            }
        }

        return array_values(array_unique($out));
    }

    public static function vimeoIdFromUrl(?string $url): ?string
    {
        return Vimeo::idFromUrl($url);
    }

    /**
     * @return list<array{icon: string, title: string, body: string}>
     */
    protected static function sanitizeWhyChooseText(string $text): string
    {
        $text = str_replace(['—', '–'], ', ', $text);
        $text = preg_replace('/\s*,\s*,+/', ', ', $text) ?? $text;
        $text = preg_replace('/\s{2,}/', ' ', $text) ?? $text;

        return trim($text);
    }

    /**
     * @return array<string, string>
     */
    protected static function whyChooseShortBodiesByTitle(): array
    {
        return [
            'East Africa specialists' => __('Local guides and planners across Kenya, Tanzania, and Uganda.'),
            'Bespoke pacing & design' => __('Day-by-day plans built around your dates, fitness, and budget.'),
            'Safety you can feel' => __('Vetted vehicles, lodges, and honest briefings before you travel.'),
            'Conservation-minded travel' => __('Partners who employ locally and respect fragile habitats.'),
            'Transparent planning' => __('Clear pricing, drive times, and season advice upfront.'),
            'Support before & after' => __('One contact from enquiry through travel and follow-up.'),
        ];
    }

    /**
     * @return list<array{icon: string, title: string, body: string}>
     */
    protected static function normalizeWhyChooseItems(mixed $raw): array
    {
        $shortBodies = self::whyChooseShortBodiesByTitle();

        $defaults = [
            ['icon' => '', 'title' => __('East Africa specialists'), 'body' => $shortBodies['East Africa specialists']],
            ['icon' => '', 'title' => __('Bespoke pacing & design'), 'body' => $shortBodies['Bespoke pacing & design']],
            ['icon' => '', 'title' => __('Safety you can feel'), 'body' => $shortBodies['Safety you can feel']],
            ['icon' => '', 'title' => __('Conservation-minded travel'), 'body' => $shortBodies['Conservation-minded travel']],
            ['icon' => '', 'title' => __('Transparent planning'), 'body' => $shortBodies['Transparent planning']],
            ['icon' => '', 'title' => __('Support before & after'), 'body' => $shortBodies['Support before & after']],
        ];

        if (! is_array($raw) || count($raw) === 0) {
            return $defaults;
        }

        $out = [];
        foreach ($raw as $row) {
            if (! is_array($row)) {
                continue;
            }
            $title = self::sanitizeWhyChooseText(trim((string) ($row['title'] ?? '')));
            if ($title === '') {
                continue;
            }
            $icon = trim((string) ($row['icon'] ?? ''));
            if (mb_strlen($icon) > 32) {
                $icon = mb_substr($icon, 0, 32);
            }
            $body = self::sanitizeWhyChooseText(trim((string) ($row['body'] ?? '')));
            if (isset($shortBodies[$title])) {
                $body = $shortBodies[$title];
            }
            $out[] = [
                'icon' => $icon,
                'title' => $title,
                'body' => $body,
            ];
        }

        return count($out) > 0 ? $out : $defaults;
    }
}
