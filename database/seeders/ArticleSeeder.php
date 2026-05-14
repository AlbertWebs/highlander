<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::query()->delete();

        $title = 'Conquer the Roof of Africa: 7-Day Mount Kilimanjaro Adventure';
        $slug = 'mount-kilimanjaro-7-day-adventure';
        $excerpt = 'Dive into the lasting adventure and the breathtaking beauty of Africa\'s highest mountain. Rising majestically above the plains of Tanzania, Mount Kilimanjaro offers an unforgettable journey through lush rainforests, alpine deserts, dramatic landscapes, and snow-capped peaks.';

        Article::query()->create([
            'slug' => $slug,
            'title' => $title,
            'excerpt' => Str::limit($excerpt, 500),
            'body' => $this->bodyHtml(),
            'featured_image' => 'https://images.unsplash.com/photo-1632854385904-e3b76814e478?auto=format&fit=crop&w=1600&q=80',
            'published_at' => now(),
            'is_active' => true,
            'meta_title' => Str::limit($title.' | Highlanders Nature Trails', 255),
            'meta_description' => Str::limit('Seven days on Mount Kilimanjaro: rainforest to Uhuru Peak with professional guides, full-board trekking, and acclimatisation built in.', 500),
        ]);
    }

    private function bodyHtml(): string
    {
        return <<<'HTML'
<p>Whether you are an experienced hiker or a passionate adventurer chasing your first summit, this expedition is designed to inspire, challenge, and transform you.</p>

<h2>Day 1 — Arrival in Tanzania &amp; welcome briefing</h2>
<p>Your Kilimanjaro adventure begins upon arrival in Moshi, the charming gateway town to the mountain. Our professional team will warmly welcome you and transfer you to your hotel for relaxation and preparation. In the evening, enjoy a detailed trekking briefing from experienced mountain guides covering:</p>
<ul>
<li>Trekking expectations</li>
<li>Safety procedures</li>
<li>Altitude awareness</li>
<li>Equipment checks</li>
</ul>
<p>Spend the night resting comfortably as anticipation builds for the climb ahead.</p>

<h2>Day 2 — Rainforest trek to Mandara Camp</h2>
<p>After breakfast, drive to the park gate where registration takes place before beginning your ascent. The trail winds through dense tropical rainforest filled with towering trees, exotic birds, and playful colobus monkeys. As you hike deeper into the mountain, the cool air and vibrant scenery create an unforgettable first impression of Kilimanjaro.</p>
<p><strong>Destination:</strong> Mandara Camp<br><strong>Elevation:</strong> approx. 2,700 m</p>

<h2>Day 3 — Moorland trek to Horombo Camp</h2>
<p>Leave the rainforest behind and enter the stunning moorland zone where giant groundsels and unique alpine vegetation dominate the landscape. The views become increasingly spectacular as the summit reveals itself in the distance. This stage offers excellent opportunities for photography and acclimatisation.</p>
<p><strong>Destination:</strong> Horombo Camp<br><strong>Elevation:</strong> approx. 3,720 m</p>

<h2>Day 4 — Acclimatisation &amp; scenic exploration</h2>
<p>Acclimatisation is essential for a successful summit. Today is dedicated to helping your body adjust to the altitude while exploring the surrounding landscapes. Take a light hike with your guide, enjoy panoramic mountain views, and witness the dramatic beauty of Kilimanjaro&apos;s alpine environment. This extra day significantly improves summit success rates and allows you to fully absorb the mountain experience.</p>

<h2>Day 5 — Trek to Kibo Camp</h2>
<p>Today&apos;s trek leads through the alpine desert zone — a surreal landscape of volcanic rock, open skies, and breathtaking silence. As you approach Kibo Camp, excitement builds for the summit attempt ahead. After an early dinner, rest briefly before preparing for the midnight ascent.</p>
<p><strong>Destination:</strong> Kibo Camp<br><strong>Elevation:</strong> approx. 4,700 m</p>

<h2>Day 6 — Summit day: Uhuru Peak</h2>
<p>Around midnight, begin the final ascent under a sky filled with stars. This is the most challenging yet most rewarding part of the expedition. As dawn breaks over Africa, reach the legendary summit <strong>Uhuru Peak</strong>, the highest point on the continent at 5,895 meters. Celebrate your achievement with unforgettable sunrise views above the clouds before descending to lower altitude for rest.</p>
<p><strong>Summit:</strong> Uhuru Peak</p>

<h2>Day 7 — Descent &amp; departure</h2>
<p>After breakfast, continue descending through the rainforest back to the park gate where successful climbers receive their summit certificates. Return to Moshi for a refreshing shower, celebration meal, and departure preparations. Leave Tanzania carrying unforgettable memories, incredible photographs, and the pride of conquering Africa&apos;s tallest mountain.</p>

<h2>Package highlights</h2>
<ul>
<li>Professional English-speaking mountain guides</li>
<li>Full-board accommodation during the trek</li>
<li>Scenic camping or hut accommodation</li>
<li>Airport transfers</li>
<li>Park entry fees</li>
<li>Summit certificates</li>
<li>Emergency support and safety equipment</li>
</ul>

<h2>Why choose this Kilimanjaro adventure?</h2>
<ul>
<li>High summit success rate</li>
<li>Experienced local guides</li>
<li>Breathtaking scenic routes</li>
<li>Safe and professionally organised expeditions</li>
<li>Perfect for solo travellers, couples, groups, and corporate teams</li>
</ul>

<h2>Book your adventure today</h2>
<p>The mountain is calling. Experience the thrill, beauty, and triumph of standing on the Roof of Africa. Start your Kilimanjaro journey with us and create memories that will last a lifetime.</p>
HTML;
    }
}
