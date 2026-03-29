<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Destination;
use App\Models\Mountain;
use App\Models\SafariExperience;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@highlander.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        SiteSetting::setValue('total_visitors', '12480');
        SiteSetting::setValue('hero_headline', 'Discover Africa Beyond the Ordinary Safari Experiences');
        SiteSetting::setValue('hero_subheadline', 'Luxury safaris, mountain adventures, and unforgettable journeys.');
        SiteSetting::setValue('hero_video_source', 'vimeo');
        SiteSetting::setValue('hero_video_url', 'https://vimeo.com/1177988644');
        SiteSetting::setValue('hero_mode', 'single');
        SiteSetting::setValue('hero_carousel_interval', '10');
        SiteSetting::setValue('hero_video_urls', json_encode([
            'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4',
            'https://videos.pexels.com/video-files/2495382/2495382-hd_1920_1080_25fps.mp4',
        ]));
        SiteSetting::setValue('hero_link_1_label', '');
        SiteSetting::setValue('hero_link_1_url', '');
        SiteSetting::setValue('hero_link_2_label', '');
        SiteSetting::setValue('hero_link_2_url', '');
        SiteSetting::setValue('hero_icon_url', '');
        SiteSetting::setValue('welcome_title', 'Discover Africa Beyond the Ordinary—|Bespoke safaris.|Unforgettable journeys.');
        SiteSetting::setValue('welcome_body', "We design private safaris and nature expeditions across East Africa—from open savanna to highland trails—tailored to your dates, pace, and the wildlife you hope to see.\n\nOur guides, vehicles, and partner lodges are chosen for safety, comfort, and respect for the wild, so you travel with confidence and return with memories that last.");
        SiteSetting::setValue('welcome_learn_more_label', 'Plan My Safari');
        SiteSetting::setValue('welcome_learn_more_url', '');
        SiteSetting::setValue('welcome_card_1_media_type', 'image');
        SiteSetting::setValue('welcome_card_1_vimeo_url', '');
        SiteSetting::setValue('welcome_card_1_image_url', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&h=1200&q=80');
        SiteSetting::setValue('welcome_card_1_overlay', 'The wild opens up when your itinerary is built around you.');
        SiteSetting::setValue('welcome_card_1_link', '');
        SiteSetting::setValue('welcome_card_2_media_type', 'image');
        SiteSetting::setValue('welcome_card_2_vimeo_url', '');
        SiteSetting::setValue('welcome_card_2_image_url', 'https://images.unsplash.com/photo-1529699211952-734e80c4d42b?auto=format&fit=crop&w=900&h=1200&q=80');
        SiteSetting::setValue('welcome_card_2_stat', '15+');
        SiteSetting::setValue('welcome_card_2_overlay', 'The best way to know Africa is on safari—with every day shaped for you.');
        SiteSetting::setValue('welcome_card_2_link', '');
        SiteSetting::setValue('cta_title', 'Ready for your next adventure?');
        SiteSetting::setValue('cta_body', 'Speak with our travel designers and build an itinerary tailored to you.');
        SiteSetting::setValue('about_hero_title', 'Our Story');
        SiteSetting::setValue('about_hero_subtitle', 'Passionate guides, sustainable travel, unforgettable horizons.');
        SiteSetting::setValue('about_content', '<p>We are a team of explorers and conservation advocates dedicated to sharing Africa\'s wonders responsibly.</p>');
        SiteSetting::setValue('site_hours', 'Mon – Fri : 8:00 – 16:00');
        SiteSetting::setValue('contact_email', 'hello@highlander.test');
        SiteSetting::setValue('contact_phone', '+254 700 000 000');
        SiteSetting::setValue('contact_address', 'Nairobi, Kenya');
        SiteSetting::setValue('contact_map_embed_url', 'https://www.openstreetmap.org/export/embed.html?bbox=36.76,-1.34,36.86,-1.24&layer=mapnik&marker=-1.286389,36.817223');
        SiteSetting::setValue('footer_credits', '© 2026 Highlanders Nature Trails . All rights reserved | Powered By <a href="https://designekta.com" target="_blank" rel="noopener noreferrer">Designekta Studios</a>');

        $tours = [
            ['title' => 'Serengeti Signature Safari', 'price' => 4890, 'days' => 7],
            ['title' => 'Kilimanjaro Lemosho Route', 'price' => 3200, 'days' => 8],
            ['title' => 'Okavango Delta Escape', 'price' => 6200, 'days' => 6],
        ];
        foreach ($tours as $i => $t) {
            Tour::query()->updateOrCreate(
                ['slug' => Str::slug($t['title'])],
                [
                    'title' => $t['title'],
                    'description' => 'A curated journey with expert guides, premium lodges, and flexible pacing.',
                    'price' => $t['price'],
                    'duration_days' => $t['days'],
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => $i,
                ]
            );
        }

        $dest = [
            ['name' => 'Masai Mara', 'slug' => 'masai-mara'],
            ['name' => 'Okavango', 'slug' => 'okavango'],
            ['name' => 'Drakensberg', 'slug' => 'drakensberg'],
            ['name' => 'Zanzibar Coast', 'slug' => 'zanzibar-coast'],
        ];
        foreach ($dest as $i => $d) {
            Destination::query()->updateOrCreate(
                ['slug' => $d['slug']],
                [
                    'name' => $d['name'],
                    'description' => 'Iconic landscapes, rich culture, and world-class hospitality.',
                    'is_active' => true,
                    'sort_order' => 10 - $i,
                ]
            );
        }

        Mountain::query()->updateOrCreate(
            ['slug' => 'mount-kenya'],
            [
                'name' => 'Mount Kenya',
                'description' => 'Glacial valleys and afro-alpine flora on Africa\'s second-highest peak.',
                'elevation_m' => 5199,
                'is_active' => true,
            ]
        );

        SafariExperience::query()->updateOrCreate(
            ['slug' => 'big-five-classic'],
            [
                'title' => 'Big Five Classic',
                'description' => 'Morning and evening game drives in prime wildlife corridors.',
                'duration' => '5 days',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Testimonial::query()->firstOrCreate(
            ['name' => 'Elena M.'],
            [
                'role' => 'Traveller',
                'country' => 'Spain',
                'safari_type' => __('Luxury Safari'),
                'quote' => 'Every detail was flawless—from the guide to the camps. We will return.',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true,
                'show_on_about' => true,
                'sort_order' => 1,
            ]
        );

        Testimonial::query()->firstOrCreate(
            ['name' => 'James K.'],
            [
                'role' => 'Traveller',
                'country' => 'United Kingdom',
                'quote' => 'An unforgettable safari experience. The guides were knowledgeable and the entire journey was perfectly organized.',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true,
                'show_on_about' => true,
                'sort_order' => 2,
            ]
        );

        Testimonial::query()->whereIn('name', ['Elena M.', 'James K.'])->update(['show_on_about' => true]);

        Article::query()->updateOrCreate(
            ['slug' => 'first-light-on-the-mara'],
            [
                'title' => 'First Light on the Mara',
                'excerpt' => 'Notes from an early morning balloon and what silence sounds like above the grasslands.',
                'body' => '<p>The sun lifts over the horizon in bands of apricot and rose. Below, the Mara breathes.</p><p>We drift with the wind, cameras quiet, hearts full.</p>',
                'published_at' => now()->subDays(3),
                'is_active' => true,
                'meta_title' => 'First Light on the Mara | Highlanders Nature Trails',
                'meta_description' => 'Travel notes from the Masai Mara at dawn—safari field stories from Highlanders Nature Trails.',
            ]
        );

        $kwBrand = 'Highlanders Nature Trails, Highlanders Nature Trails safari, nature trails Africa, African nature tours';
        $kwSafari = 'luxury African safari, East Africa safari tours, Kenya safari packages, Tanzania safari, bespoke safari itinerary, private safari guide, wildlife safari, Big Five safari, game drive safari, safari conservancy, luxury safari lodges';
        $kwMountains = 'Mount Kenya trekking, Kilimanjaro climb, African mountain expeditions, high altitude trekking, alpine safari, hiking safari Africa';
        $kwTrust = 'responsible safari travel, ecotourism Africa, conservation travel, expert safari guides';

        $seoPages = [
            'home' => [
                'meta_title' => 'Highlanders Nature Trails | Luxury African Safaris & Nature Expeditions',
                'meta_description' => 'Plan your dream safari with Highlanders Nature Trails—expert guides, Big Five wildlife, mountain treks, and tailor-made East Africa itineraries. Enquire for a bespoke nature journey.',
                'meta_keywords' => $kwBrand.', '.$kwSafari.', '.$kwMountains.', '.$kwTrust.', photography safari, honeymoon safari Africa, small group safari',
            ],
            'about' => [
                'meta_title' => 'About Us | Highlanders Nature Trails',
                'meta_description' => 'Meet Highlanders Nature Trails—East Africa safari and adventure experts. Our vision, team, fleet, safety standards, and sustainability commitments.',
                'meta_keywords' => $kwBrand.', safari company Kenya, African tour operator, sustainable safari, ethical wildlife tourism, bespoke travel Africa, about safari guides',
            ],
            'mountains' => [
                'meta_title' => 'Mountain Treks & Alpine Trails | Highlanders Nature Trails',
                'meta_description' => 'Trek iconic African peaks and nature trails with Highlanders Nature Trails—Mount Kenya, Kilimanjaro routes, expert mountain support, and high-altitude expeditions.',
                'meta_keywords' => $kwBrand.', '.$kwMountains.', summit trekking Africa, acclimatization trek, mountain safari combo',
            ],
            'explore-africa' => [
                'meta_title' => 'Explore Africa Destinations | Highlanders Nature Trails',
                'meta_description' => 'Discover safari destinations with Highlanders Nature Trails—from savanna wildlife corridors to coast and crater—curated regions and expert itinerary design.',
                'meta_keywords' => $kwBrand.', '.$kwSafari.', Masai Mara safari, Serengeti tours, Okavango Delta, Zanzibar beach safari, East Africa destinations',
            ],
            'safari' => [
                'meta_title' => 'Safari Experiences & Game Drives | Highlanders Nature Trails',
                'meta_description' => 'Classic and luxury safari experiences with Highlanders Nature Trails—game drives, private conservancies, expert rangers, and wildlife itineraries across East Africa.',
                'meta_keywords' => $kwBrand.', '.$kwSafari.', morning game drive, evening safari, walking safari, birding safari Africa',
            ],
            'gallery' => [
                'meta_title' => 'Safari & Nature Gallery | Highlanders Nature Trails',
                'meta_description' => 'Safari moments, mountain vistas, and African wilderness through our lens—inspiration from Highlanders Nature Trails journeys.',
                'meta_keywords' => $kwBrand.', safari photography, Africa wildlife photos, travel gallery East Africa, nature landscapes',
            ],
            'articles' => [
                'meta_title' => 'Safari Travel Insights & Stories | Highlanders Nature Trails',
                'meta_description' => 'Safari tips, destination guides, and field stories from Highlanders Nature Trails—plan smarter African adventures with advice from seasoned guides.',
                'meta_keywords' => $kwBrand.', safari travel tips, Africa packing list, when to visit Kenya safari, wildlife seasons, travel blog Africa',
            ],
            'contact' => [
                'meta_title' => 'Contact & Trip Enquiries | Highlanders Nature Trails',
                'meta_description' => 'Contact Highlanders Nature Trails to plan your safari or mountain expedition—personalized quotes, itinerary design, and responsive support from our team.',
                'meta_keywords' => $kwBrand.', book African safari, safari enquiry, tailor-made safari quote, contact safari operator Kenya',
            ],
            'plan-my-safari' => [
                'meta_title' => 'Plan Your Dream Safari | Highlanders Nature Trails',
                'meta_description' => 'Tell us your travel dates, destinations, and style—request a bespoke safari itinerary from Highlanders Nature Trails. We respond with a tailored proposal.',
                'meta_keywords' => $kwBrand.', plan safari Kenya, custom safari quote, tailor-made African safari, private safari planning',
            ],
            'privacy' => [
                'meta_title' => 'Privacy Policy | Highlanders Nature Trails',
                'meta_description' => 'How Highlanders Nature Trails collects, uses, and protects your personal information when you browse our site or enquire about travel.',
                'meta_keywords' => $kwBrand.', privacy policy, data protection, travel website privacy',
            ],
            'terms' => [
                'meta_title' => 'Terms of Use | Highlanders Nature Trails',
                'meta_description' => 'Terms and conditions for using the Highlanders Nature Trails website and related services.',
                'meta_keywords' => $kwBrand.', terms of use, website terms, travel operator terms',
            ],
            'photo-credits' => [
                'meta_title' => 'Photo Credits | Highlanders Nature Trails',
                'meta_description' => 'Image and photography credits for stock and licensed visuals used on the Highlanders Nature Trails website.',
                'meta_keywords' => $kwBrand.', photo credits, image attribution, Unsplash credits',
            ],
        ];

        foreach ($seoPages as $key => $meta) {
            SeoMeta::query()->updateOrCreate(
                ['page_key' => $key],
                $meta
            );
        }

        $this->call(AboutPageSeeder::class);
    }
}
