<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'slug' => 'first-light-on-the-mara',
                'title' => 'First Light on the Mara',
                'excerpt' => 'Notes from an early morning balloon over the grasslands—and what silence sounds like when the savanna is still waking.',
                'featured_image' => 'https://images.unsplash.com/photo-1516426122078-c23e763b01e0?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(21),
                'meta_title' => 'First Light on the Mara | Highlanders Nature Trails',
                'meta_description' => 'Travel notes from the Masai Mara at dawn—balloon safari field stories from Highlanders Nature Trails.',
                'body' => $this->bodyFirstLight(),
            ],
            [
                'slug' => 'serengeti-migration-timing-your-visit',
                'title' => 'The Serengeti Migration: Timing Your Visit',
                'excerpt' => 'Rain, grass, and instinct drive one of Earth’s great movements. Here is how seasons shape where the herds are—and how we plan around them.',
                'featured_image' => 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(18),
                'meta_title' => 'Serengeti Migration Timing | Highlanders Nature Trails',
                'meta_description' => 'When to visit the Serengeti for the Great Migration—seasonal patterns, river crossings, and itinerary tips.',
                'body' => $this->bodySerengeti(),
            ],
            [
                'slug' => 'walking-safaris-pace-and-presence',
                'title' => 'Walking Safaris: Why Pace Changes Everything',
                'excerpt' => 'Without an engine between you and the bush, your senses reorder. Wind, spoor, and bird calls become the map.',
                'featured_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(15),
                'meta_title' => 'Walking Safaris in East Africa | Highlanders Nature Trails',
                'meta_description' => 'What to expect on a guided walking safari—safety, silence, and a deeper connection to the landscape.',
                'body' => $this->bodyWalking(),
            ],
            [
                'slug' => 'okavango-when-the-waters-rise',
                'title' => 'Okavango Waters: When the Delta Fills',
                'excerpt' => 'Flood pulses from distant highlands rewrite channels and islands each year. Boats, mokoro, and the rhythm of water shape every day.',
                'featured_image' => 'https://images.unsplash.com/photo-1472552946069-7729f4a6e0c8?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(12),
                'meta_title' => 'Okavango Delta Seasons | Highlanders Nature Trails',
                'meta_description' => 'Understanding Okavango flood timing—water levels, activities, and how we build flexible delta itineraries.',
                'body' => $this->bodyOkavango(),
            ],
            [
                'slug' => 'responsible-photography-on-game-drives',
                'title' => 'Responsible Photography on Game Drives',
                'excerpt' => 'Great images should never cost an animal its peace. Flash, distance, and patience are part of the craft—and the ethics.',
                'featured_image' => 'https://images.unsplash.com/photo-1523805009345-7448847a0d08?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(9),
                'meta_title' => 'Ethical Wildlife Photography | Highlanders Nature Trails',
                'meta_description' => 'Field guidelines for safari photography—respecting wildlife, guides, and other guests while capturing the moment.',
                'body' => $this->bodyPhoto(),
            ],
            [
                'slug' => 'packing-for-a-two-week-east-africa-circuit',
                'title' => 'Packing for a Two-Week East Africa Circuit',
                'excerpt' => 'Layers for cool mornings, dust for dry tracks, and a bag that fits small aircraft. A practical list from years on the road.',
                'featured_image' => 'https://images.unsplash.com/photo-1482192597420-4817fdd7e904?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(6),
                'meta_title' => 'Safari Packing List — Two Weeks | Highlanders Nature Trails',
                'meta_description' => 'What to pack for a multi-stop East Africa safari—clothing, optics, health kit, and luggage limits.',
                'body' => $this->bodyPacking(),
            ],
            [
                'slug' => 'community-conservancies-and-your-fees',
                'title' => 'Community Conservancies and Where Your Fees Go',
                'excerpt' => 'Bed-night levies and conservancy fees fund rangers, schools, and grazing agreements. Transparency builds trust—and better landscapes.',
                'featured_image' => 'https://images.unsplash.com/photo-1591825729267-cceb732e9f94?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDays(3),
                'meta_title' => 'Community Conservancies Explained | Highlanders Nature Trails',
                'meta_description' => 'How safari tourism supports community-owned conservancies in Kenya and Tanzania—fees, impact, and long-term stewardship.',
                'body' => $this->bodyConservancies(),
            ],
            [
                'slug' => 'above-the-clouds-mount-kenya-afternoon',
                'title' => 'Above the Clouds: An Afternoon on Mount Kenya',
                'excerpt' => 'Teleki Valley and giant groundsels feel like another planet. Altitude humbles you; the silence between gusts does the rest.',
                'featured_image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80',
                'published_at' => now()->subDay(),
                'meta_title' => 'Mount Kenya Alpine Trek | Highlanders Nature Trails',
                'meta_description' => 'Field notes from the afro-alpine zone on Mount Kenya—acclimatization, flora, and combining peaks with safari.',
                'body' => $this->bodyKenya(),
            ],
        ];

        foreach ($articles as $row) {
            Article::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'body' => $row['body'],
                    'featured_image' => $row['featured_image'],
                    'published_at' => $row['published_at'],
                    'is_active' => true,
                    'meta_title' => $row['meta_title'],
                    'meta_description' => $row['meta_description'],
                ]
            );
        }
    }

    private function bodyFirstLight(): string
    {
        return <<<'HTML'
<p>The basket sways once—just enough to remind you that you are suspended over a landscape older than memory. Then the burners hush, and the Mara opens below in shades of umber and sage. This is the hour when night still clings to the long grass but the sky has already made its decision: today will be clear.</p>
<h2>Sound before shape</h2>
<p>From a vehicle, you learn the reserve in bursts: engine note, radio crackle, tyres on rutted tracks. From a balloon, the first vocabulary is <em>quiet</em>. You hear wildebeest murmuring in loose groups, a fish eagle’s distant complaint, the soft friction of wind over the canopy. Binoculars become less about magnification and more about choosing where to place your attention.</p>
<p>Our guides often say that dawn is when predators finish business started in the dark—and when herbivores negotiate space with posture rather than speed. Watching that negotiation from above is humbling. You are not part of the food web in that moment; you are a witness. The responsibility in that word is something we take seriously on every itinerary we design.</p>
<h2>Why height changes empathy</h2>
<p>Elevation strips away the temptation to chase sightings. You cannot “turn left” for a closer lion look; you learn to accept the frame the wind gives you. Paradoxically, that constraint deepens appreciation. You begin to notice drainage lines, salt pans, the way fire history writes itself in scrub mosaic. It is a geography lesson with your heart rate slightly elevated—not from fear, but from wonder.</p>
<p>When we land and the champagne toast feels earned, guests often ask whether the balloon is “worth” the early wake-up. We answer with a question: when else will you hear the Mara before you see it? If the answer matters to you, the wake-up is not a cost; it is the price of a rare alignment between body, light, and landscape.</p>
<blockquote><p>“Silence is not the absence of noise. It is the presence of enough space to listen.”</p></blockquote>
<p>We would love to weave a balloon morning into your Mara chapter—paired with ground days that balance spectacle with rest. The sky is not the whole story; it is the prologue.</p>
HTML;
    }

    private function bodySerengeti(): string
    {
        return <<<'HTML'
<p>The Great Migration is often described as a single event with a calendar-friendly address. In truth, it is a rolling negotiation between rainfall, grass chemistry, and the inherited memory of herds that have crossed these plains for millennia. Our job is not to promise a river crossing on a Tuesday, but to place you where probability and comfort align.</p>
<h2>Green season vs dry season</h2>
<p>After the short rains, the southern plains flush with protein-rich grasses. Wildebeest calving concentrates predators and drama in a relatively compact theatre. Later, as moisture retreats, columns push north and west, seeking biomass that can sustain hundreds of thousands of mouths. The “dry” months sharpen visibility—dust, golden light, and exposed waterholes—but also raise stakes around water access.</p>
<p>We build shoulder-season buffers into itineraries: an extra night near a crossing point, a flexible flight window, a lodge that does not lock you into a single gate. Migration travel rewards humility; the herds do not consult brochures.</p>
<h2>River crossings: spectacle and ethics</h2>
<p>When animals mass at a bank, tension pulls tight as a drum skin. Crocodiles read the water differently than ungulates do; tourists read cameras differently than guides do. We brief guests on patience—crossings cannot be summoned—and on kindness to other vehicles. A respectful queue preserves sightlines and reduces stress on animals already making life-or-death decisions.</p>
<p>If your dream is drama, we will chase ethical proximity to probability. If your dream is solitude, we may steer you to peripheral corridors where zebra and gazelle stage quieter movements. Either way, the Serengeti rewards those who release the fantasy of control and stay curious about what the day actually offers.</p>
<h2>Planning takeaways</h2>
<ul>
<li>Book windows, not single dates—especially around crossings.</li>
<li>Mix central Serengeti nights with northern or western nodes depending on month.</li>
<li>Trust your guide’s morning plan; they synthesize radio intel, weather, and animal behaviour.</li>
</ul>
<p>Ask us for a month-by-month conversation. Maps are static; rain is not.</p>
HTML;
    }

    private function bodyWalking(): string
    {
        return <<<'HTML'
<p>A walking safari is not a slow game drive. It is a different discipline—one where sound carries farther than sight, and where the trail is sometimes an old elephant path braided through thorn scrub. Your guide is not only interpreting tracks; they are managing risk with a calm that comes from repetition and respect.</p>
<h2>What changes when you step down</h2>
<p>Without a chassis around you, wind direction matters again. You learn to pause when baboons cough alarm, to read the posture of giraffe—apparently still but wired for information. Time stretches. A hundred metres can take twenty minutes if the guide is teaching you to differentiate between black and white rhino browse, or to notice the tiny engineering of termite mounds.</p>
<p>Guests often describe walking days as “more tiring yet more restful” than driving days. The paradox makes sense: your body works, but your attention settles. There is no glass between you and the scent of sun-warmed dust after rain.</p>
<h2>Safety and trust</h2>
<p>We never walk casually where big cats are known to be on a kill, and we never treat proximity as a contest. Weapons and protocols exist, but the core tool is judgement—learned through seasons of reading animal tolerance. Your role is to follow briefings, cluster when asked, and silence phones. The reward is legitimacy: you are a temporary participant in a landscape, not a spectator behind glass.</p>
<h2>Who it suits</h2>
<p>Reasonable fitness helps—heat and terrain add up—but attitude matters more than marathon times. Curious, patient travellers who can savour small revelations will love walking. If your priority is ticking off a checklist at speed, keep the vehicle days and add one walk as a palate cleanser.</p>
<p>We can blend walking camps with classic driving circuits so you never have to choose a single mode. The bush has room for both; your nervous system will tell you which day felt more honest.</p>
HTML;
    }

    private function bodyOkavango(): string
    {
        return <<<'HTML'
<p>The Okavango is a miracle of hydrology: water that should drain to the sea pools inland instead, fanning into channels, lagoons, and islands that shift like slow thoughts. That means “best time to visit” is a conversation about water level, not just temperature.</p>
<h2>Flood pulse and local rain</h2>
<p>Remote Angolan rainfall eventually arrives in Botswana as a rising tide through the panhandle—often months later. Local thunderstorms complicate the picture: a storm near camp can make mokoro trips magical one afternoon and impractical the next. Flexibility is not a buzzword here; it is infrastructure.</p>
<p>We favour camps with multiple activity types—boat, vehicle, mokoro—so guides can pivot without deflating your trip. When water is high, slip through lilies and watch jacana print the surface. When it is low, islands consolidate game around remaining channels, and leopard sightings can spike along wooded margins.</p>
<h2>What to expect on the water</h2>
<p>Mokoro polers work with astonishing precision in channels barely wider than the vessel. Hippos redefine the word “obstacle.” You will learn to read a ripple differently—to ask whether it is wind, fish, or something heavier. Evenings often end with firelight and frog chorus, the sky enormous above the reeds.</p>
<h2>Itinerary philosophy</h2>
<p>Pair the Delta with drier reserves if you want contrast—salt pans, Kalahari edges, or a few nights in a more traditional savanna setting. Water teaches patience; dry country teaches scale. Together they make southern Africa feel complete.</p>
<p>Tell us your month and mobility comfort; we will recommend a water-to-land ratio that feels adventurous without feeling chaotic.</p>
HTML;
    }

    private function bodyPhoto(): string
    {
        return <<<'HTML'
<p>Everyone arrives with a camera—phone, mirrorless, or long glass that looks like it belongs on a spacecraft. The goal is universal: bring the wild home without stealing peace from the animals that make the wild real.</p>
<h2>Distance is dignity</h2>
<p>Long lenses exist so that animals can behave naturally. Crowding a sighting for a tighter frame is not creativity; it is pressure. We coach guests to work with light and moment rather than proximity. Often the best image is the honest one: animals relaxed, ears forward, no stress ripple through the herd.</p>
<h2>Flash and night drives</h2>
<p>Flash photography on nocturnal mammals can disrupt vision and hunting behaviour. Where red-filter spotlights are permitted, we use them sparingly and never to harass. If you are photographing people—guides, staff, community hosts—ask first. Consent is as important in a village as in a city.</p>
<h2>Sharing responsibly</h2>
<p>Geotagging can expose den sites or rhino territories to poachers faster than conservation teams can adapt. We encourage generic location tags or delayed posts. Your followers will still feel transported; sensitive species will stay safer.</p>
<h2>The human frame</h2>
<p>Some of the most powerful safari images include human elements: a tracker’s profile, a guide’s hand signalling silence, laughter around a fire. Credit the people in the frame. Safari is collaboration; photographs should honour that.</p>
<p>We love guests who treat images as souvenirs of attention rather than trophies of intrusion. The bush gives generously when we meet it halfway.</p>
HTML;
    }

    private function bodyPacking(): string
    {
        return <<<'HTML'
<p>Two weeks across East Africa might include cool highland mornings, hot savanna middays, and a coastal breeze if you finish on the Indian Ocean. Your bag should be modular: layers you can peel, neutrals that hide dust, and nothing that embarrasses you at a lodge dinner.</p>
<h2>Luggage reality</h2>
<p>Many inter-camp flights use small aircraft with strict weight limits—often 15 kg in soft bags. We send a checklist early, but the rule of thumb is: one primary bag, one daypack, optics around your neck, not in the hold. Laundry in camp is normal; overpacking is the enemy.</p>
<h2>Clothing</h2>
<ul>
<li>Light long-sleeved shirts for sun and insect protection.</li>
<li>A warm mid-layer and a packable shell for dawn drives and open vehicles.</li>
<li>Wide-brim hat plus a buff for dust on dry tracks.</li>
<li>Comfortable closed shoes for walking; sandals for camp.</li>
</ul>
<h2>Optics and tech</h2>
<p>Binoculars matter as much as cameras—8x42 is a versatile sweet spot. Bring spare batteries, a small power bank, and adapters suited to lodges you will visit. If you shoot video, prioritize cards and stability; safari days generate more footage than you expect.</p>
<h2>Health and comfort</h2>
<p>Pack prescriptions in original containers, sunscreen you will actually reapply, lip balm, and moisturizer—aircraft and dust are dehydrating. Consult your clinician about malaria prophylaxis where relevant. A compact personal first-aid kit with blister plasters and antihistamine covers minor annoyances before they become distractions.</p>
<p>Travel light, plan laundry, and leave room for the intangible: the jacket you will loan someone on a chilly morning, or the book you finish by lantern light. Those extras weigh nothing and mean everything.</p>
HTML;
    }

    private function bodyConservancies(): string
    {
        return <<<'HTML'
<p>Community conservancies expand habitat beyond national park boundaries by aligning local livelihoods with wildlife presence. They are not charity projects; they are contracts—often negotiated over years—about land use, grazing rotations, and revenue sharing.</p>
<h2>Where the money flows</h2>
<p>Bed-night fees and conservancy levies typically fund security patrols, wildlife monitoring, compensation schemes for livestock losses, and social investments like scholarships or clinics. Transparency varies, and we favour partners who publish impact summaries and involve community governance structures in decisions.</p>
<p>When you stay in a conservancy property, you are not “buying a view.” You are paying into a corridor that lets elephants move, lions disperse, and grass recover after drought. That is infrastructure in ecological form.</p>
<h2>What guests can do</h2>
<p>Ask questions—respectfully—about how your lodge contributes beyond marketing language. Visit community projects when offered; buy crafts directly from artisans. Tip guides and staff in line with camp norms; good wages and tip pools often circle back into local economies.</p>
<h2>The long horizon</h2>
<p>Conservancies face pressure from climate shifts, development corridors, and political cycles. Tourism alone cannot solve conservation, but aligned tourism buys time for science, policy, and local stewardship to synchronize. Your trip can be both pleasure and participation if you choose partners who treat fees as trust, not just revenue.</p>
<p>We are happy to map conservancy stays into your route—not as an add-on, but as a coherent strategy for impact and exceptional sightings alike.</p>
HTML;
    }

    private function bodyKenya(): string
    {
        return <<<'HTML'
<p>Mount Kenya is not Kilimanjaro’s taller sibling in the brochure sense; it is a more intricate mountain—glaciers hanging above valleys carved by ice that has mostly retreated, leaving moraines and tarns that glitter like spilled coins. An afternoon above the clouds can feel like trespassing in a garden designed for giants.</p>
<h2>Afro-alpine theatre</h2>
<p>Giant groundsel and lobelia look imported from science fiction. They are simply evolution’s answer to cold nights and intense UV. Guereza colobus bark somewhere in the podocarp forest below; closer to the treeline, hyrax whistles stitch the silence. Altitude narrows your vocabulary to essentials: water, pace, gratitude.</p>
<p>We do not rush acclimatization. A sluggish headache is information, not failure. Guides who know the mountain translate weather signs—cap clouds, wind shifts—into pace adjustments that keep you safer than any slogan.</p>
<h2>Pairing peaks with plains</h2>
<p>Many travellers combine a few trekking days with the Mara or Laikipia. The contrast is therapeutic: vertical solitude, then horizontal abundance. Your legs may disagree at first, but your imagination will not. The mountain teaches scale; the savanna teaches flow.</p>
<h2>Practical notes</h2>
<ul>
<li>Train with hills and stairs; descents punish unprepared quads.</li>
<li>Layer obsessively—sun at noon, frost at dawn.</li>
<li>Respect park rules and ranger guidance; erosion is already a threat on busy trails.</li>
</ul>
<p>If you have ever wanted silence with altitude, Mount Kenya delivers—one careful step at a time, above a sea of cloud that makes the lowlands feel like another continent.</p>
HTML;
    }
}
