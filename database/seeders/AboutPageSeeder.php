<?php

namespace Database\Seeders;

use App\Models\AboutCoreValue;
use App\Models\AboutFleetImage;
use App\Models\AboutFleetSubsection;
use App\Models\AboutPageSetting;
use App\Models\AboutSafetyPoint;
use App\Models\AboutSustainabilityItem;
use App\Models\AboutTeamRole;
use App\Models\AboutVisionMissionCard;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        if (AboutPageSetting::query()->exists()) {
            return;
        }

        AboutPageSetting::query()->create([
            'hero_title' => 'About Highlanders Nature Trails',
            'hero_subtitle' => 'Guiding unforgettable journeys across Africa’s wild landscapes.',
            'hero_image' => null,
            'intro_heading' => 'East Africa’s Trusted Safari and Adventure Experts',
            'intro_paragraph_1' => 'Highlanders Nature Trails is a premier safari and adventure company dedicated to creating unforgettable journeys across East Africa. We specialize in wildlife safaris, mountain expeditions, cultural tours, and personalized travel experiences designed to connect travelers with nature and local communities.',
            'intro_paragraph_2' => 'Our team combines deep local knowledge with professional service to ensure every journey is safe, comfortable, and memorable.',
            'intro_image' => null,
            'intro_cta_label' => 'Plan My Safari',
            'fleet_heading' => 'Our Fleet and Equipment',
            'fleet_body' => 'We maintain a modern fleet of well-equipped safari vehicles designed for comfort and safety. Our equipment is regularly serviced and inspected to ensure reliability in all terrain and weather conditions.',
            'team_heading' => 'Meet Our Team',
            'team_body' => 'Our experienced team includes professional guides, drivers, porters, chefs, and coordinators who are passionate about delivering exceptional travel experiences.',
            'team_image' => null,
            'safety_heading' => 'Safety and Compliance',
            'safety_body' => 'We prioritize the safety and well-being of our guests. Our operations comply with tourism regulations and international safety standards.',
            'safety_image' => null,
            'core_values_section_title' => 'Our Core Values',
            'sustainability_section_title' => 'Our Sustainability Initiatives',
            'testimonials_section_title' => 'What Travelers Say',
            'cta_heading' => 'Ready to Start Your Adventure?',
            'cta_body' => 'Let our experienced team design a safari or mountain journey tailored just for you.',
            'cta_button_label' => 'Plan My Safari',
        ]);

        $vision = [
            ['title' => 'Our Vision', 'body' => 'To become the leading provider of authentic African travel experiences that inspire adventure and promote sustainable tourism.', 'icon' => '🌍', 'sort_order' => 1],
            ['title' => 'Our Mission', 'body' => 'To deliver exceptional safari and adventure services while ensuring safety, comfort, and unforgettable memories for every traveler.', 'icon' => '🎯', 'sort_order' => 2],
            ['title' => 'Our Promise', 'body' => 'We are committed to responsible tourism, community support, and protecting Africa’s natural heritage.', 'icon' => '🤝', 'sort_order' => 3],
        ];
        foreach ($vision as $row) {
            AboutVisionMissionCard::query()->create($row + ['is_active' => true]);
        }

        $core = [
            ['title' => 'Respect for Nature', 'description' => 'We tread lightly and support conservation through how we travel and who we partner with.', 'icon' => '🌿', 'sort_order' => 1],
            ['title' => 'Safety First', 'description' => 'Rigorous checks, trained staff, and clear protocols on every departure.', 'icon' => '🛡️', 'sort_order' => 2],
            ['title' => 'Integrity', 'description' => 'Honest advice, transparent pricing, and dependable execution.', 'icon' => '⚖️', 'sort_order' => 3],
            ['title' => 'Excellence', 'description' => 'Attention to detail from first enquiry to farewell.', 'icon' => '⭐', 'sort_order' => 4],
            ['title' => 'Passion for Adventure', 'description' => 'We love the wild—and it shows in how we plan and guide.', 'icon' => '🥾', 'sort_order' => 5],
            ['title' => 'Customer Satisfaction', 'description' => 'Your comfort, pace, and goals shape every itinerary.', 'icon' => '💚', 'sort_order' => 6],
            ['title' => 'Sustainability', 'description' => 'Long-term thinking for landscapes, wildlife, and communities.', 'icon' => '♻️', 'sort_order' => 7],
            ['title' => 'Community Support', 'description' => 'Prioritizing local employment, suppliers, and fair partnerships.', 'icon' => '🏘️', 'sort_order' => 8],
        ];
        foreach ($core as $row) {
            AboutCoreValue::query()->create($row + ['is_active' => true]);
        }

        foreach ([
            ['caption' => 'Safari vehicle', 'sort_order' => 1],
            ['caption' => 'Camping equipment', 'sort_order' => 2],
            ['caption' => 'Mountain gear', 'sort_order' => 3],
        ] as $fi) {
            AboutFleetImage::query()->create($fi + ['image' => null, 'is_active' => true]);
        }

        foreach ([
            ['title' => 'Vehicles for Transfers', 'body' => 'Comfortable, air-conditioned vehicles for airport and lodge connections.', 'sort_order' => 1],
            ['title' => 'Custom Safari Vehicles', 'body' => 'Open-sided or pop-top 4×4 rigs built for wildlife viewing and photography.', 'sort_order' => 2],
            ['title' => 'Mountain Equipment', 'body' => 'Quality camping and trekking kit maintained for high-altitude routes.', 'sort_order' => 3],
        ] as $fs) {
            AboutFleetSubsection::query()->create($fs + ['is_active' => true]);
        }

        foreach ([
            'Tour Coordinators', 'Professional Guides', 'Drivers', 'Mountain Guides', 'Chefs', 'Porters',
        ] as $i => $label) {
            AboutTeamRole::query()->create(['label' => $label, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        foreach ([
            'Licensed and insured operations',
            'Certified professional guides',
            'Emergency response planning',
            'Safety equipment provided',
            'First aid trained staff',
        ] as $i => $text) {
            AboutSafetyPoint::query()->create(['point_text' => $text, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        $sus = [
            ['title' => 'Environmental conservation', 'description' => 'Supporting habitats and low-impact travel practices.', 'icon' => '🌳', 'sort_order' => 1],
            ['title' => 'Community support', 'description' => 'Investing in local projects and fair livelihoods.', 'icon' => '🤲', 'sort_order' => 2],
            ['title' => 'Local employment', 'description' => 'Guides, staff, and partners from the regions we visit.', 'icon' => '👥', 'sort_order' => 3],
            ['title' => 'Responsible tourism', 'description' => 'Small groups, ethical wildlife viewing, and respect for culture.', 'icon' => '📍', 'sort_order' => 4],
            ['title' => 'Wildlife protection', 'description' => 'Aligning with parks and conservancies that put species first.', 'icon' => '🦁', 'sort_order' => 5],
            ['title' => 'Cultural preservation', 'description' => 'Meaningful visits that benefit hosts and traditions.', 'icon' => '🎭', 'sort_order' => 6],
        ];
        foreach ($sus as $row) {
            AboutSustainabilityItem::query()->create($row + ['is_active' => true]);
        }
    }
}
