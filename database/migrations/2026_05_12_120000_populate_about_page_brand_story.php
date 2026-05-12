<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('about_page_settings')) {
            return;
        }

        $brand = 'Highlanders Nature Trails & Safaris';

        $settingId = (int) DB::table('about_page_settings')->orderBy('id')->value('id');
        if ($settingId === 0) {
            return;
        }

        DB::table('about_page_settings')->where('id', $settingId)->update([
            'hero_title' => $brand,
            'hero_subtitle' => <<<'HTML'
<p>We are passionate about mountains, adventure, and the wild beauty of East Africa. Whether you are summiting the peaks of Mount Kenya or Kilimanjaro, trekking off-the-beaten-path trails, or embarking on a safari across the region’s game reserves and national parks, we deliver exceptional, personalized experiences every step of the way.</p>
<p><strong>Backed by expert guides and a deep love for the outdoors, we ensure your journey is not just a trip, but a transformational adventure.</strong> From rugged mountain ascents to wildlife encounters, we have you covered.</p>
HTML,
            'intro_heading' => 'Your adventure starts here',
            'intro_paragraph_1' => <<<HTML
<p>At {$brand}, we believe the best journeys weave together wild landscapes, careful planning, and genuine human connection. We design each itinerary around your pace, your goals, and the seasons, so you can focus on the horizon, the track in the dust, and the stories you will carry home.</p>
<p>Our team lives this work: early starts on the trail, patient wildlife encounters, and evenings spent swapping tales around the fire. That rhythm shapes how we host you, with warmth, professionalism, and respect for the places and communities that welcome us.</p>
HTML,
            'intro_paragraph_2' => <<<'HTML'
<blockquote class="about-pullquote"><p>When you travel with us, you are not just a tourist, you are part of a story of family, discovery, and legacy.</p></blockquote>
<p>So lace up your boots, grab your sense of adventure, and let us write the next chapter, together.</p>
HTML,
            'core_values_section_title' => 'Why family is our foundation',
            'sustainability_section_title' => 'Our impact: footprints of hope',
            'fleet_heading' => 'Built for mountains and safaris',
            'fleet_body' => <<<'HTML'
<p>Reliable vehicles, mountain-ready kit, and field-tested logistics are what turn a bold idea into a safe, comfortable journey. We maintain and equip our fleet for remote roads, long game drives, and highland camps, so you can trust the details while you live the adventure.</p>
HTML,
            'team_heading' => 'Meet the people behind the journey',
            'team_body' => <<<'HTML'
<p>Today our team is a vibrant tapestry of guides, conservationists, and storytellers from diverse backgrounds, united by a shared mission. We have created livelihoods, fostered friendships, and built a community where everyone plays a part in preserving the wild places we love.</p>
<p>We extend that ethos beyond our own circle, offering training and mentorship to aspiring guides, especially women and youth, because protecting nature is a collective effort.</p>
HTML,
            'safety_heading' => 'Safety and stewardship',
            'safety_body' => <<<'HTML'
<p>Adventure should feel bold, not reckless. We combine careful route planning, clear communication, and experienced leadership so you can explore with confidence, from thin-air camps to open savanna.</p>
HTML,
            'cta_heading' => 'The wild is calling. How will you answer?',
            'cta_body' => <<<'HTML'
<p><strong>Join us. Wander with purpose.</strong> Every summit and every safari helps fuel initiatives that turn barren land into forests, support education where it is needed most, and prove that tourism can be a force for good.</p>
HTML,
            'updated_at' => now(),
        ]);

        $visionBodies = [
            <<<'HTML'
<p>Our roots run deep, both in the soil of East Africa’s landscapes and in the bonds of family. What began as one person’s love for the environment grew into a movement that intertwines adventure, conservation, and community.</p>
<p>My background in environmental conservation planted the seed. When I shared that passion with my wife, the dream took real shape: a venture that would showcase East Africa’s beauty and help protect it for those who come after us.</p>
<p>We started by training our own family, guiding skills, wildlife knowledge, and sustainable tourism, then watched something remarkable happen: enthusiasm spread. Friends, neighbors, and guests wanted in. That spark became the flame you see today.</p>
HTML,
            <<<'HTML'
<p>We began with our own household, teaching guiding craft, wildlife literacy, and how to move lightly through fragile places. As we hiked misty forests, camped under star-filled skies, and tracked wildlife across golden savannas, our family’s curiosity became contagious.</p>
<p>Today, guides, conservationists, and storytellers carry that same spirit into every departure. We hire locally, invest in skills, and treat each team member as a steward of the landscapes we share with you.</p>
HTML,
            <<<'HTML'
<p>We do not only take you to the wild, we immerse you in it. From Kilimanjaro’s snow line to Mount Kenya’s forests and the Great Migration in the Maasai Mara, every itinerary is laced with meaning.</p>
<h3>How we travel with purpose</h3>
<ul>
<li><strong>Conservation in action:</strong> A portion of proceeds supports reforestation, wildlife protection, and clean water initiatives, tourism that gives back.</li>
<li><strong>Leave no trace:</strong> Low-impact travel keeps the trails and camps we love pristine for the next generation.</li>
<li><strong>Cultural connection:</strong> We collaborate with local communities, honoring the people who call these lands home.</li>
</ul>
<p>When you choose us, you join a circle that believes the earth we cherish today must thrive tomorrow.</p>
HTML,
        ];

        $visionTitles = [
            'Our story: passion, family, and the wild',
            'From a spark to a flame',
            'Adventure with a deeper purpose',
        ];

        $visionIds = DB::table('about_vision_mission_cards')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id');

        foreach ($visionIds as $i => $id) {
            if (! isset($visionTitles[$i])) {
                break;
            }
            DB::table('about_vision_mission_cards')->where('id', $id)->update([
                'title' => $visionTitles[$i],
                'body' => $visionBodies[$i],
                'updated_at' => now(),
            ]);
        }

        $core = [
            [
                'title' => 'Little explorers',
                'description' => '<p>From their first hikes, our children learn to identify bird calls, track prints, and plant trees, because conservation is not a lecture, it is an adventure lived with mud on their boots and binoculars in hand.</p>',
            ],
            [
                'title' => 'Generational wisdom',
                'description' => '<p>Elders share stories of the land while younger guides bring fresh energy and ideas. Together we bridge the past and future of sustainable tourism, respecting tradition while innovating in the field.</p>',
            ],
            [
                'title' => 'Community roots',
                'description' => '<p>We extend our family ethos outward, training and mentoring aspiring guides, especially women and youth, because safeguarding nature succeeds when opportunity is shared widely.</p>',
            ],
            [
                'title' => 'Conservation in action',
                'description' => '<p>A portion of our proceeds flows into reforestation, wildlife protection, and clean water initiatives. We want every departure to leave landscapes stronger, not merely visited.</p>',
            ],
            [
                'title' => 'Leave no trace',
                'description' => '<p>We practice and teach low-impact travel, thoughtful campsites, careful waste handling, and routes that protect sensitive habitats, so the places we love stay wild.</p>',
            ],
            [
                'title' => 'Cultural connection',
                'description' => '<p>We collaborate closely with local communities, sourcing fairly and visiting respectfully, because honoring people and protecting ecosystems go hand in hand.</p>',
            ],
            [
                'title' => 'Discovery',
                'description' => '<p>We design space for wonder, whether that is your first glimpse of elephants at dawn or the quiet confidence you find above the clouds on a long climb.</p>',
            ],
            [
                'title' => 'Legacy',
                'description' => '<p>The earth we cherish today must thrive tomorrow. Your journey helps fund schools, trees, and livelihoods, proof that travel can be a lasting gift to East Africa.</p>',
            ],
        ];

        $coreIds = DB::table('about_core_values')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->pluck('id');
        foreach ($coreIds as $i => $id) {
            if (! isset($core[$i])) {
                break;
            }
            DB::table('about_core_values')->where('id', $id)->update([
                'title' => $core[$i]['title'],
                'description' => $core[$i]['description'],
                'updated_at' => now(),
            ]);
        }

        $sus = [
            [
                'title' => 'Rooted in conservation',
                'description' => '<p>Every tree we plant is a promise, to the earth, to future generations, and to the wild places that inspire us. Community planting days restore catchments, buffer climate stress, and rebuild habitat for wildlife.</p>',
            ],
            [
                'title' => 'Hands in the soil',
                'description' => '<p>We organize planting days where laughter meets saplings taking root, small acts that add up to healthier watersheds and greener ridges above the trails we walk with guests.</p>',
            ],
            [
                'title' => 'A call to allies',
                'description' => '<p>Travelers, neighbors, and conservation partners are all invited to contribute, because the best journeys move us together toward a greener tomorrow.</p>',
            ],
            [
                'title' => 'Education where it matters',
                'description' => '<p>In the arid north of Kenya, we support learning for children from pastoralist communities, quality schooling, nourishing meals, and a supportive environment where pencils replace sticks and curiosity opens new paths.</p>',
            ],
            [
                'title' => 'More than books',
                'description' => '<p>We teach that livestock is not the only legacy, knowledge can break cycles of poverty and empower young people to steward their land with pride and skill.</p>',
            ],
            [
                'title' => 'Your journey changes lives',
                'description' => '<p>Every summit and safari helps fund forests, classrooms, and community initiatives, turning your adventure into hope that outlasts the footprints we leave behind.</p>',
            ],
        ];

        $susIds = DB::table('about_sustainability_items')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->pluck('id');
        foreach ($susIds as $i => $id) {
            if (! isset($sus[$i])) {
                break;
            }
            DB::table('about_sustainability_items')->where('id', $id)->update([
                'title' => $sus[$i]['title'],
                'description' => $sus[$i]['description'],
                'updated_at' => now(),
            ]);
        }

        $fleetSubs = [
            [
                'title' => 'Comfortable transfers',
                'body' => '<p>Air-conditioned vehicles for airport and lodge connections, your first and last miles in East Africa should feel smooth after long flights.</p>',
            ],
            [
                'title' => 'Safari-proven 4×4 rigs',
                'body' => '<p>Pop-top and photography-friendly layouts for long days on game drives, with shade, charging, and space for gear.</p>',
            ],
            [
                'title' => 'Mountain-ready equipment',
                'body' => '<p>Tents, kitchen kits, and trekking support maintained for high-altitude routes and changing weather on the big peaks.</p>',
            ],
            [
                'title' => 'Communications and navigation',
                'body' => '<p>GPS, radios, and satellite options where routes demand dependable contact and positioning in remote terrain.</p>',
            ],
            [
                'title' => 'Recovery and spares',
                'body' => '<p>Tools, spare wheels, jacks, and recovery gear on extended departures, prepared for rough tracks far from the workshop.</p>',
            ],
        ];

        $fleetIds = DB::table('about_fleet_subsections')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->pluck('id');
        foreach ($fleetIds as $i => $id) {
            if (! isset($fleetSubs[$i])) {
                break;
            }
            DB::table('about_fleet_subsections')->where('id', $id)->update([
                'title' => $fleetSubs[$i]['title'],
                'body' => $fleetSubs[$i]['body'],
                'updated_at' => now(),
            ]);
        }

        $safety = [
            'Licensed, insured operations with clear emergency plans',
            'Professional guides trained in mountain and bush protocols',
            'First-aid capable crews and well-maintained safety equipment',
            'Vehicle checks before long transfers and remote legs',
            'Guest briefings so everyone knows the day’s rhythm and signals',
        ];

        $safetyIds = DB::table('about_safety_points')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->pluck('id');
        foreach ($safetyIds as $i => $id) {
            if (! isset($safety[$i])) {
                break;
            }
            DB::table('about_safety_points')->where('id', $id)->update([
                'point_text' => $safety[$i],
                'updated_at' => now(),
            ]);
        }

        $roles = [
            'Lead safari guides',
            'Mountain and trekking guides',
            'Conservation mentors',
            'Drivers and logistics',
            'Camp chefs and hospitality',
            'Porters and trail teams',
        ];

        $roleIds = DB::table('about_team_roles')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->pluck('id');
        foreach ($roleIds as $i => $id) {
            if (! isset($roles[$i])) {
                break;
            }
            DB::table('about_team_roles')->where('id', $id)->update([
                'label' => $roles[$i],
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Content migration: no automatic rollback.
    }
};
