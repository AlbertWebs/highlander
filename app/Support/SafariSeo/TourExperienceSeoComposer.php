<?php

namespace App\Support\SafariSeo;

use App\Models\Tour;
use Illuminate\Support\Str;

/**
 * Builds long-form SEO narrative, FAQ schema payload, and section HTML for public tour / experience pages.
 * Output is escaped for safe {!! !!} rendering. Cache at the call site (see PageController).
 */
final class TourExperienceSeoComposer
{
    private const KEYWORD_PHRASES = [
        'Kenya safaris', 'African safaris', 'luxury safaris', 'budget-friendly safari options',
        'family safaris', 'Masai Mara safaris', 'Amboseli safaris', 'Tsavo safaris',
        'safari tours in Kenya', 'wildlife tours', 'Big Five safari', 'adventure travel Kenya',
        'custom safari packages',
    ];

    /**
     * @return array{
     *   introduction: string,
     *   overview: string,
     *   highlights: string,
     *   wildlife: string,
     *   accommodation: string,
     *   logistics: string,
     *   best_time: string,
     *   packing: string,
     *   evenings: string,
     *   conservation: string,
     *   why_highlanders: string,
     *   destination_block: string,
     *   itinerary_intro: string,
     *   conclusion: string,
     *   perfect_for: list<string>,
     *   highlight_cards: list<array{title:string,body:string}>,
     *   quick_facts: list<array{label:string,value:string}>,
     *   faq: list<array{question:string,answer:string}>,
     *   faq_json_ld: array<string, mixed>,
     *   image_alt_hero: string,
     *   suggested_meta_description: string,
     *   estimated_word_count: int,
     * }
     */
    public static function compose(Tour $tour): array
    {
        $title = $tour->title;
        $days = (int) ($tour->duration_days ?? 0);
        $daysLabel = $days > 0
            ? trans_choice(':count day|:count days', $days, ['count' => $days])
            : __('a thoughtfully paced journey');
        $priceLine = __('We will quote transparently once we know your dates, lodging style, and party size.');
        $regions = self::regionsFromTour($tour);
        $regionSentence = $regions['sentence'];
        $seed = crc32((string) $tour->slug);
        $kwPick = fn (int $i): string => self::KEYWORD_PHRASES[($seed + $i) % count(self::KEYWORD_PHRASES)];

        $intro = self::paragraphBlock([
            __('Picture dawn over acacia silhouettes, the air cool and expectant, as your guide reads the morning like a map written in tracks and bird calls. The itinerary titled :title is designed to translate that feeling into a clear day-by-day rhythm, without rushing the moments that make :region_line unforgettable.', ['title' => $title, 'region_line' => $regionSentence]),
            __('Whether you are researching :kw1 for the first time or comparing :kw2 across East Africa, this page walks you through what makes this particular package distinct: pacing, wildlife focus, comfort level, and how Highlanders Nature Trails keeps logistics calm so you can stay present.', ['kw1' => $kwPick(0), 'kw2' => $kwPick(1)]),
            __('If you are travelling as a couple, a family, or a small private group, you will find notes here on :kw3 and :kw4 so expectations stay honest and excitement stays high.', ['kw3' => $kwPick(2), 'kw4' => $kwPick(3)]),
            __('A premium safari is not only about species lists; it is about rhythm: how mornings feel, how midday heat is handled, how afternoons return you to camp with stories already forming. We write itineraries that respect attention spans as much as mileage tables.', []),
            __('Across :kw1 and :kw2, travellers often discover that the best memories arrive in unscripted minutes: a lioness stretching in dust, a rainbow on distant rain, a guide pausing because something in the bush has changed tone.', ['kw1' => $kwPick(5), 'kw2' => $kwPick(6)]),
        ]);

        $overview = self::paragraphBlock([
            __('Safari Overview: :title is structured as a :daysLabel experience that balances game viewing with rest, meals, and transfers that do not drain the day. Our planning philosophy is simple: arrive prepared, move with purpose, and leave margin for the sightings you cannot schedule.', ['title' => $title, 'daysLabel' => $daysLabel]),
            __('For travellers comparing :kw1 with :kw2, the difference is often pacing and privacy. We prefer routes and timings that reduce queueing at gates, avoid harsh midday heat when possible, and keep camp arrivals feeling relaxed rather than rushed.', ['kw1' => $kwPick(4), 'kw2' => $kwPick(5)]),
            $priceLine,
            __('When guests ask about :kw1, we answer with logistics and ethics: respectful distances around wildlife, guides trained to interpret behaviour, and vehicles maintained for rough roads without turning the day into an endurance test.', ['kw1' => $kwPick(6)]),
            __('If you are weighing :kw1 against a shorter escape, we will be candid about trade-offs: sometimes fewer nights in fewer places yields a calmer trip than a stampede of park gates.', ['kw1' => $kwPick(7)]),
            __('For guests focused on :kw1, we can emphasise species targets while still protecting the relaxed tempo that makes sightings feel earned rather than chased.', ['kw1' => $kwPick(8)]),
        ]);

        $highlights = '<ul class="mt-4 list-none space-y-3">'
            .self::li(__('Private guiding mindset with space for questions, photography, and quiet observation'))
            .self::li(__('Itinerary rhythm tuned for wildlife activity peaks and comfortable camp arrivals'))
            .self::li(__('Clear communication on what is flexible versus fixed, so you can adapt confidently'))
            .self::li(__('Support for :kw1 and :kw2 without turning the trip into a generic checklist', ['kw1' => $kwPick(7), 'kw2' => $kwPick(8)]))
            .'</ul>'
            .self::paragraphBlock([
                __('Highlights are not only “what you see,” but how you see it: the quality of light, the patience at a sighting, the small interpretive details that turn animals into a story of landscape and season.', []),
            ]);

        $wildlife = self::paragraphBlock([
            __('Wildlife Experience: East Africa rewards curiosity. Depending on season and route, guests may encounter elephant herds with slow, conversational movement, lion prides negotiating shade, cheetah scanning open ground, and the smaller dramas (mongooses, raptors, lapwings) that make :kw1 feel alive beyond the Big Five headline.', ['kw1' => $kwPick(9)]),
            __('If your dream is a :kw1 moment, we set expectations honestly: nature is not a menu. What we promise is experienced guiding, ethical approach distances, and the patience to read signs rather than chase noise.', ['kw1' => $kwPick(10)]),
            __('Photographers often appreciate early starts and stable positioning; families appreciate clarity and safety framing; honeymooners appreciate intimacy and unhurried meals. Your guide adapts within the guardrails of park rules and animal wellbeing.', []),
            __('Seasonal shifts change the theatre: migratory birds, calving stories, cats on open ground after rain, or elephants digging for minerals. We describe what is likely, not guaranteed, so wonder stays clean and pressure stays low.', []),
            __('On :kw1, the goal is not “more sightings,” but richer interpretation: tracks, alarm calls, posture, and landscape context that turn a drive into a masterclass you can feel.', ['kw1' => $kwPick(11)]),
        ]);

        $accommodation = self::paragraphBlock([
            __('Accommodation Experience: lodging style shapes the emotional texture of a safari: whether you prefer classic tented romance, family-friendly layouts, or a more boutique lodge feel. We align camp selection with road distances, season, and your comfort priorities, keeping nights restful so mornings feel generous.', []),
            __('For guests weighing :kw1 against tighter budgets, we can often preserve the “safari magic” by prioritising location and guide quality while adjusting room category. Ask us where the trade-offs are wisest.', ['kw1' => $kwPick(11)]),
        ]);

        $logistics = self::paragraphBlock([
            __('Transport & Logistics: smooth transfers are the invisible backbone of a premium safari. We plan realistic driving windows, clear meet points, and contingency thinking for weather and road conditions, so you spend fewer minutes uncertain and more minutes absorbed in the landscape.', []),
            __('Adventure travel Kenya itineraries should feel bold, not chaotic. That means well-maintained vehicles, spare communication where needed, and drivers who understand that confidence is part of hospitality.', []),
        ]);

        $bestTime = self::paragraphBlock([
            __('Best Time to Visit: seasonality changes vegetation, water, and animal movement. We will tell you plainly what each month tends to favour: green-season drama and birding, dry-season concentration near water, or shoulder-season quiet; then align your dates with what you want to feel most.', []),
            __('If you are combining :region_line with other regions, we help you sequence altitude, climate, and recovery days so the trip feels cohesive rather than compressed.', ['region_line' => $regionSentence]),
        ]);

        $packing = self::paragraphBlock([
            __('What to Carry: layers for cool mornings and warm middays, a brimmed hat, sunscreen, binoculars, and a simple daypack. Neutral-toned clothing is practical in vehicles and on foot. We send a tailored checklist after booking, but the goal is always the same: light, versatile, and camera-friendly.', []),
            __('Many guests also pack a compact power bank, a refillable water bottle, and a soft lens cloth: small objects that remove friction on long drives between wildlife sightings.', []),
        ]);

        $evenings = self::paragraphBlock([
            __('Evenings on safari have their own cadence: a shower, a drink by the fire, the sound of hyena whoops carrying across darkness, and conversations that wander from constellations to tomorrow’s route. We build schedules that protect those hours instead of cramming them with unnecessary movement.', []),
            __('Meals become punctuation marks: brunch after a long morning, high tea before an afternoon drive, dinner under lanterns. Energy stays steady and moods stay generous.', []),
        ]);

        $conservation = self::paragraphBlock([
            __('Conservation context matters: many of the landscapes that host :kw1 also support communities and wildlife corridors under real pressure. We favour partners who invest locally, employ fairly, and treat anti-poaching and habitat stewardship as operational priorities, not marketing footnotes.', ['kw1' => $kwPick(6)]),
            __('When you travel with Highlanders, you are not only buying a seat in a vehicle; you are voting for a style of tourism that tries to leave routes, reputations, and relationships better than we found them.', []),
        ]);

        $why = self::paragraphBlock([
            __('Why Choose Highlanders Nature Trails: we are East Africa specialists who treat safaris as relationships, not transactions. You get honest recommendations, careful pacing, and a team that understands that the best wildlife tours are built from trust as much as geography.', []),
            __('From :kw1 to :kw2, our aim is the same: an itinerary that feels bespoke in the field, even when it begins as a published package.', ['kw1' => $kwPick(12), 'kw2' => $kwPick(13)]),
            __('If you want a :title shaped around a celebration, a photography goal, or a slower family rhythm, tell us: we excel at :kw1 that still respect park realities and animal welfare.', ['title' => $title, 'kw1' => $kwPick(14)]),
        ]);

        $destinationBlock = self::paragraphBlock([
            __('Destination Overview: :region_line We can expand with conservancy nuances, community perspectives, and seasonal movement patterns once we know your month of travel.', ['region_line' => $regionSentence]),
            __('For many guests, the first safari is a threshold experience: senses heightened, time bending around sunsets. Our job is to keep the world big while making the plan feel human-sized.', []),
        ]);

        $itineraryIntro = self::paragraphBlock([
            __('Detailed Itinerary (day-by-day): the section below translates each day into movement, meals, and wildlife opportunities. Where days are still being refined, use the planner to tell us your dates; we will align camps and transfers to match the season.', []),
        ]);

        $conclusion = self::paragraphBlock([
            __('Conclusion: if :title already feels like “your” trip, the next step is simple: send dates, party composition, and any must-see species or landscapes. We will respond with a clear proposal and a conversation that feels like planning with experts, not selling.', ['title' => $title]),
            __('Ready when you are. Scroll to the planner, choose this itinerary, and we will begin shaping the details.', []),
        ]);

        $perfectFor = [
            __('Couples seeking intimacy and unhurried game drives'),
            __('Families wanting clear safety framing and engaging guiding'),
            __('Photographers prioritising light, positioning, and patience'),
            __('Travellers combining bush and cultural interests across Kenya'),
        ];

        $highlightCards = [
            ['title' => __('Wildlife windows'), 'body' => __('Early starts and smart routing to align with animal activity and softer light.')],
            ['title' => __('Comfort & clarity'), 'body' => __('Lodging and transfers chosen for rest, realism on road times, and transparent expectations.')],
            ['title' => __('Ethical viewing'), 'body' => __('Respectful distances, calm positioning, and guides who read behaviour, not pressure animals.')],
            ['title' => __('Tailoring'), 'body' => __('Custom safari packages built from this template with your dates, budget band, and pace.')],
        ];

        $quickFacts = [
            ['label' => __('Experience'), 'value' => $title],
            ['label' => __('Duration'), 'value' => $days > 0 ? $daysLabel : __('On request')],
            ['label' => __('Ideal for'), 'value' => __('Kenya & East Africa wildlife travellers')],
            ['label' => __('Style'), 'value' => __('Premium guided safari pacing')],
        ];

        $faq = self::buildFaq($tour, $daysLabel, $regionSentence, $kwPick);

        $htmlBlob = $intro.$overview.$highlights.$wildlife.$accommodation.$logistics.$bestTime.$packing.$evenings.$conservation.$why.$destinationBlock.$itineraryIntro.$conclusion;
        $wc = str_word_count(strip_tags(str_replace(['&nbsp;', "\xc2\xa0"], ' ', $htmlBlob)));

        return [
            'introduction' => $intro,
            'overview' => $overview,
            'highlights' => $highlights,
            'wildlife' => $wildlife,
            'accommodation' => $accommodation,
            'logistics' => $logistics,
            'best_time' => $bestTime,
            'packing' => $packing,
            'evenings' => $evenings,
            'conservation' => $conservation,
            'why_highlanders' => $why,
            'destination_block' => $destinationBlock,
            'itinerary_intro' => $itineraryIntro,
            'conclusion' => $conclusion,
            'perfect_for' => $perfectFor,
            'highlight_cards' => $highlightCards,
            'quick_facts' => $quickFacts,
            'faq' => $faq['items'],
            'faq_json_ld' => $faq['json_ld'],
            'image_alt_hero' => __('Safari vehicle and golden savanna light, :title', ['title' => strip_tags($tour->title)]),
            'suggested_meta_description' => Str::limit(
                strip_tags(__('Book :title, :daysLabel. :kw Premium Kenya safari planning with Highlanders Nature Trails.', [
                    'title' => $tour->title,
                    'daysLabel' => $daysLabel,
                    'kw' => $kwPick(0),
                ])),
                158
            ),
            'estimated_word_count' => $wc,
        ];
    }

    /**
     * @return array{sentence: string, labels: list<string>}
     */
    private static function regionsFromTour(Tour $tour): array
    {
        $hay = strtolower($tour->title.' '.$tour->slug);
        $labels = [];
        $map = [
            'mara' => 'Maasai Mara / Mara ecosystem',
            'masai' => 'Maasai Mara / Mara ecosystem',
            'amboseli' => 'Amboseli & Kilimanjaro views',
            'tsavo' => 'Tsavo wilderness corridors',
            'samburu' => 'Samburu / northern Kenya specials',
            'nakuru' => 'Great Rift lakes & Nakuru region',
            'laikipia' => 'Laikipia conservancies',
            'kenya' => 'Kenya’s flagship parks and conservancies',
            'serengeti' => 'Northern Tanzania savanna',
            'okavango' => 'Okavango Delta waterways',
            'chogoria' => 'Mount Kenya highlands',
            'sirimon' => 'Mount Kenya highlands',
            'naromoru' => 'Mount Kenya highlands',
            'kilimanjaro' => 'Kilimanjaro foothills & montane transition',
        ];
        foreach ($map as $needle => $label) {
            if (str_contains($hay, $needle) && ! in_array($label, $labels, true)) {
                $labels[] = $label;
            }
        }
        if ($labels === []) {
            $labels[] = __('Kenya’s diverse ecosystems and conservancies');
        }
        $shown = array_slice($labels, 0, 3);
        $sentence = implode(', ', $shown);
        $sentence .= count($labels) > 3 ? __(' and further extensions as needed.') : '';
        $sentence .= '.';

        return ['labels' => $labels, 'sentence' => $sentence];
    }

    private static function paragraphBlock(array $paragraphs): string
    {
        $out = '';
        foreach ($paragraphs as $p) {
            $out .= '<p class="mt-4 text-[1.05rem] leading-[1.8] text-ink/90 sm:text-lg">'.e($p).'</p>';
        }

        return $out;
    }

    private static function li(string $text): string
    {
        return '<li class="flex gap-3 rounded-xl border border-secondary/25 bg-white/80 px-4 py-3 text-sm leading-relaxed text-ink/85 shadow-sm">'
            .'<span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-primary" aria-hidden="true"></span>'
            .'<span>'.e($text).'</span></li>';
    }

    /**
     * @param  callable(int):string  $kwPick
     * @return array{items: list<array{question:string,answer:string}>, json_ld: array<string, mixed>}
     */
    private static function buildFaq(Tour $tour, string $daysLabel, string $regionSentence, callable $kwPick): array
    {
        $items = [
            [
                'question' => __('Is :title suitable for a first safari?', ['title' => strip_tags($tour->title)]),
                'answer' => __('Yes. First-time guests appreciate the clear structure of :daysLabel and the guiding style we use on :region_sentence We explain etiquette early so you relax into the rhythm.', ['daysLabel' => $daysLabel, 'region_sentence' => $regionSentence.'.']),
            ],
            [
                'question' => __('Can you customise this itinerary?'),
                'answer' => __('Absolutely. Published packages are a starting point. We adjust camps, internal flights, and pacing to match :kw1 preferences and budget bands.', ['kw1' => $kwPick(1)]),
            ],
            [
                'question' => __('What is included versus excluded?'),
                'answer' => __('Inclusions vary by final proposal. We will itemise park fees, transport, meals, and activities in writing so you can compare apples-to-apples across :kw1 options.', ['kw1' => $kwPick(2)]),
            ],
            [
                'question' => __('How far in advance should we book?'),
                'answer' => __('Popular seasons fill earlier, especially for :kw1. If your dates are fixed, reach out as soon as you can; if flexible, we will suggest smarter windows.', ['kw1' => $kwPick(3)]),
            ],
            [
                'question' => __('Do you offer family safaris?'),
                'answer' => __('Yes. We plan sensible driving lengths, kid-friendly framing, and accommodation where family units make sense, while keeping wildlife experiences respectful and safe.', []),
            ],
            [
                'question' => __('What makes Highlanders different on a Big Five safari?'),
                'answer' => __('We prioritise calm positioning, ethical distances, and guides who can teach without performing, so your :kw1 feels grounded, not rushed.', ['kw1' => $kwPick(4)]),
            ],
        ];

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn (array $row): array => [
                '@type' => 'Question',
                'name' => $row['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($row['answer']),
                ],
            ], $items),
        ];

        return ['items' => $items, 'json_ld' => $jsonLd];
    }
}
