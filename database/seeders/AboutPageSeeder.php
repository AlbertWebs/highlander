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

        $brand = 'Highlanders Nature Trails & Safaris';

        AboutPageSetting::query()->create([
            'hero_title' => $brand,
            'hero_subtitle' => <<<'HTML'
<p>We are passionate about mountains, adventure, and the wild beauty of East Africa. Whether you are summiting the peaks of Mount Kenya or Kilimanjaro, trekking off-the-beaten-path trails, or embarking on a safari across the region’s game reserves and national parks, we deliver exceptional, personalized experiences every step of the way.</p>
<p><strong>Backed by expert guides and a deep love for the outdoors, we ensure your journey is not just a trip, but a transformational adventure.</strong> From rugged mountain ascents to wildlife encounters, we have you covered.</p>
HTML,
            'hero_image' => null,
            'intro_heading' => 'Your adventure starts here',
            'intro_paragraph_1' => <<<HTML
<p>At {$brand}, we believe the best journeys weave together wild landscapes, careful planning, and genuine human connection. We design each itinerary around your pace, your goals, and the seasons, so you can focus on the horizon, the track in the dust, and the stories you will carry home.</p>
<p>Our team lives this work: early starts on the trail, patient wildlife encounters, and evenings spent swapping tales around the fire. That rhythm shapes how we host you, with warmth, professionalism, and respect for the places and communities that welcome us.</p>
HTML,
            'intro_paragraph_2' => <<<'HTML'
<blockquote class="about-pullquote"><p>When you travel with us, you are not just a tourist, you are part of a story of family, discovery, and legacy.</p></blockquote>
<p>So lace up your boots, grab your sense of adventure, and let us write the next chapter, together.</p>
HTML,
            'intro_image' => null,
            'intro_cta_label' => 'Plan My Safari',
            'fleet_heading' => 'Built for mountains and safaris',
            'fleet_body' => <<<'HTML'
<p>Reliable vehicles, mountain-ready kit, and field-tested logistics are what turn a bold idea into a safe, comfortable journey. We maintain and equip our fleet for remote roads, long game drives, and highland camps, so you can trust the details while you live the adventure.</p>
HTML,
            'team_heading' => 'Meet the people behind the journey',
            'team_body' => <<<'HTML'
<p>Today our team is a vibrant tapestry of guides, conservationists, and storytellers from diverse backgrounds, united by a shared mission. We have created livelihoods, fostered friendships, and built a community where everyone plays a part in preserving the wild places we love.</p>
<p>We extend that ethos beyond our own circle, offering training and mentorship to aspiring guides, especially women and youth, because protecting nature is a collective effort.</p>
HTML,
            'team_image' => null,
            'safety_heading' => 'Safety and stewardship',
            'safety_body' => <<<'HTML'
<p>Adventure should feel bold, not reckless. We combine careful route planning, clear communication, and experienced leadership so you can explore with confidence, from thin-air camps to open savanna.</p>
HTML,
            'safety_image' => null,
            'core_values_section_title' => 'Why family is our foundation',
            'sustainability_section_title' => 'Our impact: footprints of hope',
            'testimonials_section_title' => 'What Travelers Say',
            'cta_heading' => 'The wild is calling. How will you answer?',
            'cta_body' => <<<'HTML'
<p><strong>Join us. Wander with purpose.</strong> Every summit and every safari helps fuel initiatives that turn barren land into forests, support education where it is needed most, and prove that tourism can be a force for good.</p>
HTML,
            'cta_button_label' => 'Plan My Safari',
        ]);

        $vision = [
            [
                'title' => 'Our story: passion, family, and the wild',
                'body' => <<<'HTML'
<p>Our roots run deep, both in the soil of East Africa’s landscapes and in the bonds of family. What began as one person’s love for the environment grew into a movement that intertwines adventure, conservation, and community.</p>
<p>My background in environmental conservation planted the seed. When I shared that passion with my wife, the dream took real shape: a venture that would showcase East Africa’s beauty and help protect it for those who come after us.</p>
<p>We started by training our own family, guiding skills, wildlife knowledge, and sustainable tourism, then watched something remarkable happen: enthusiasm spread. Friends, neighbors, and guests wanted in. That spark became the flame you see today.</p>
HTML,
                'icon' => '🌍',
                'sort_order' => 1,
            ],
            [
                'title' => 'From a spark to a flame',
                'body' => <<<'HTML'
<p>We began with our own household, teaching guiding craft, wildlife literacy, and how to move lightly through fragile places. As we hiked misty forests, camped under star-filled skies, and tracked wildlife across golden savannas, our family’s curiosity became contagious.</p>
<p>Today, guides, conservationists, and storytellers carry that same spirit into every departure. We hire locally, invest in skills, and treat each team member as a steward of the landscapes we share with you.</p>
HTML,
                'icon' => '🎯',
                'sort_order' => 2,
            ],
            [
                'title' => 'Adventure with a deeper purpose',
                'body' => <<<'HTML'
<p>We do not only take you to the wild, we immerse you in it. From Kilimanjaro’s snow line to Mount Kenya’s forests and the Great Migration in the Maasai Mara, every itinerary is laced with meaning.</p>
<h3>How we travel with purpose</h3>
<ul>
<li><strong>Conservation in action:</strong> A portion of proceeds supports reforestation, wildlife protection, and clean water initiatives, tourism that gives back.</li>
<li><strong>Leave no trace:</strong> Low-impact travel keeps the trails and camps we love pristine for the next generation.</li>
<li><strong>Cultural connection:</strong> We collaborate with local communities, honoring the people who call these lands home.</li>
</ul>
<p>When you choose us, you join a circle that believes the earth we cherish today must thrive tomorrow.</p>
HTML,
                'icon' => '🤝',
                'sort_order' => 3,
            ],
        ];
        foreach ($vision as $row) {
            AboutVisionMissionCard::query()->create($row + ['is_active' => true]);
        }

        $core = [
            ['title' => 'Little explorers', 'description' => '<p>From their first hikes, our children learn to identify bird calls, track prints, and plant trees, because conservation is not a lecture, it is an adventure lived with mud on their boots and binoculars in hand.</p>', 'icon' => '🌿', 'sort_order' => 1],
            ['title' => 'Generational wisdom', 'description' => '<p>Elders share stories of the land while younger guides bring fresh energy and ideas. Together we bridge the past and future of sustainable tourism, respecting tradition while innovating in the field.</p>', 'icon' => '🛡️', 'sort_order' => 2],
            ['title' => 'Community roots', 'description' => '<p>We extend our family ethos outward, training and mentoring aspiring guides, especially women and youth, because safeguarding nature succeeds when opportunity is shared widely.</p>', 'icon' => '⚖️', 'sort_order' => 3],
            ['title' => 'Conservation in action', 'description' => '<p>A portion of our proceeds flows into reforestation, wildlife protection, and clean water initiatives. We want every departure to leave landscapes stronger, not merely visited.</p>', 'icon' => '⭐', 'sort_order' => 4],
            ['title' => 'Leave no trace', 'description' => '<p>We practice and teach low-impact travel, thoughtful campsites, careful waste handling, and routes that protect sensitive habitats, so the places we love stay wild.</p>', 'icon' => '🥾', 'sort_order' => 5],
            ['title' => 'Cultural connection', 'description' => '<p>We collaborate closely with local communities, sourcing fairly and visiting respectfully, because honoring people and protecting ecosystems go hand in hand.</p>', 'icon' => '💚', 'sort_order' => 6],
            ['title' => 'Discovery', 'description' => '<p>We design space for wonder, whether that is your first glimpse of elephants at dawn or the quiet confidence you find above the clouds on a long climb.</p>', 'icon' => '♻️', 'sort_order' => 7],
            ['title' => 'Legacy', 'description' => '<p>The earth we cherish today must thrive tomorrow. Your journey helps fund schools, trees, and livelihoods, proof that travel can be a lasting gift to East Africa.</p>', 'icon' => '🏘️', 'sort_order' => 8],
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
            ['title' => 'Comfortable transfers', 'body' => '<p>Air-conditioned vehicles for airport and lodge connections, your first and last miles in East Africa should feel smooth after long flights.</p>', 'sort_order' => 1],
            ['title' => 'Safari-proven 4×4 rigs', 'body' => '<p>Pop-top and photography-friendly layouts for long days on game drives, with shade, charging, and space for gear.</p>', 'sort_order' => 2],
            ['title' => 'Mountain-ready equipment', 'body' => '<p>Tents, kitchen kits, and trekking support maintained for high-altitude routes and changing weather on the big peaks.</p>', 'sort_order' => 3],
            ['title' => 'Communications and navigation', 'body' => '<p>GPS, radios, and satellite options where routes demand dependable contact and positioning in remote terrain.</p>', 'sort_order' => 4],
            ['title' => 'Recovery and spares', 'body' => '<p>Tools, spare wheels, jacks, and recovery gear on extended departures, prepared for rough tracks far from the workshop.</p>', 'sort_order' => 5],
        ] as $fs) {
            AboutFleetSubsection::query()->create($fs + ['is_active' => true]);
        }

        foreach ([
            'Lead safari guides',
            'Mountain and trekking guides',
            'Conservation mentors',
            'Drivers and logistics',
            'Camp chefs and hospitality',
            'Porters and trail teams',
        ] as $i => $label) {
            AboutTeamRole::query()->create(['label' => $label, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        foreach ([
            'Licensed, insured operations with clear emergency plans',
            'Professional guides trained in mountain and bush protocols',
            'First-aid capable crews and well-maintained safety equipment',
            'Vehicle checks before long transfers and remote legs',
            'Guest briefings so everyone knows the day’s rhythm and signals',
        ] as $i => $text) {
            AboutSafetyPoint::query()->create(['point_text' => $text, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        $sus = [
            ['title' => 'Rooted in conservation', 'description' => '<p>Every tree we plant is a promise, to the earth, to future generations, and to the wild places that inspire us. Community planting days restore catchments, buffer climate stress, and rebuild habitat for wildlife.</p>', 'icon' => '🌳', 'sort_order' => 1],
            ['title' => 'Hands in the soil', 'description' => '<p>We organize planting days where laughter meets saplings taking root, small acts that add up to healthier watersheds and greener ridges above the trails we walk with guests.</p>', 'icon' => '🤲', 'sort_order' => 2],
            ['title' => 'A call to allies', 'description' => '<p>Travelers, neighbors, and conservation partners are all invited to contribute, because the best journeys move us together toward a greener tomorrow.</p>', 'icon' => '👥', 'sort_order' => 3],
            ['title' => 'Education where it matters', 'description' => '<p>In the arid north of Kenya, we support learning for children from pastoralist communities, quality schooling, nourishing meals, and a supportive environment where pencils replace sticks and curiosity opens new paths.</p>', 'icon' => '📍', 'sort_order' => 4],
            ['title' => 'More than books', 'description' => '<p>We teach that livestock is not the only legacy, knowledge can break cycles of poverty and empower young people to steward their land with pride and skill.</p>', 'icon' => '🦁', 'sort_order' => 5],
            ['title' => 'Your journey changes lives', 'description' => '<p>Every summit and safari helps fund forests, classrooms, and community initiatives, turning your adventure into hope that outlasts the footprints we leave behind.</p>', 'icon' => '🎭', 'sort_order' => 6],
        ];
        foreach ($sus as $row) {
            AboutSustainabilityItem::query()->create($row + ['is_active' => true]);
        }
    }
}
