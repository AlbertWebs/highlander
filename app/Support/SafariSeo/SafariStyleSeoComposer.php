<?php

namespace App\Support\SafariSeo;

use App\Models\SafariExperience;
use Illuminate\Support\Str;

/**
 * Long-form SEO sections for /safari/{slug} style pages (catalogue safari styles).
 */
final class SafariStyleSeoComposer
{
    private const KEYWORD_PHRASES = [
        'Kenya safaris', 'African safaris', 'luxury safaris', 'family safaris',
        'Masai Mara safaris', 'Amboseli safaris', 'Tsavo safaris', 'safari tours in Kenya',
        'wildlife tours', 'Big Five safari', 'adventure travel Kenya', 'custom safari packages',
    ];

    /**
     * @return array{
     *   introduction: string,
     *   overview: string,
     *   pacing: string,
     *   wildlife: string,
     *   lodging: string,
     *   logistics: string,
     *   best_time: string,
     *   why_highlanders: string,
     *   conclusion: string,
     *   highlight_cards: list<array{title:string,body:string}>,
     *   quick_facts: list<array{label:string,value:string}>,
     *   perfect_for: list<string>,
     *   faq: list<array{question:string,answer:string}>,
     *   faq_json_ld: array<string, mixed>,
     *   suggested_meta_description: string,
     *   estimated_word_count: int,
     * }
     */
    public static function compose(SafariExperience $style): array
    {
        $title = $style->title;
        $duration = filled($style->duration) ? $style->duration : __('Flexible duration');
        $seed = crc32((string) $style->slug);
        $kw = fn (int $i): string => self::KEYWORD_PHRASES[($seed + $i) % count(self::KEYWORD_PHRASES)];

        $intro = self::p([
            __('This safari style, :title, captures a particular rhythm of East African travel: how mornings begin, how heat is managed, and how evenings return you to camp with stories still unfolding. It is a template we refine into a firm itinerary once we know your dates and priorities.', ['title' => $title]),
            __('If you are comparing :kw1 and :kw2, use this page as a compass: what the style emphasises, where it shines seasonally, and how Highlanders Nature Trails keeps the experience premium without feeling precious.', ['kw1' => $kw(0), 'kw2' => $kw(1)]),
        ]);

        $overview = self::p([
            __('Safari Overview: :title is best understood as a curated approach to parks, conservancies, and pacing, typically discussed around :duration. We translate “style” into real decisions: gate timings, meal rhythms, and the amount of flexibility you want on each day.', ['title' => $title, 'duration' => $duration]),
            __('Guests researching :kw1 often ask whether a style is “right” for beginners. Usually yes, because the guide team carries the complexity while you focus on learning and enjoyment.', ['kw1' => $kw(2)]),
        ]);

        $pacing = self::p([
            __('Pacing & daily rhythm: we avoid treating a safari like a checklist. Instead, we sequence drives, meals, and rest so attention stays high and fatigue stays low, especially important for :kw1.', ['kw1' => $kw(3)]),
        ]);

        $wildlife = self::p([
            __('Wildlife focus: while no ethical operator can guarantee specific sightings, this style is designed to maximise meaningful encounters within park rules, especially for guests interested in :kw1 and classic savanna species.', ['kw1' => $kw(4)]),
        ]);

        $lodging = self::p([
            __('Lodging & comfort: camp and lodge selection is where “luxury safaris” become tangible: linens, views, service, and the small rituals that make a hard day feel soft. Tell us your comfort band and we will match properties honestly.', []),
        ]);

        $logistics = self::p([
            __('Transport & logistics: we plan realistic transfers, clear meet points, and contingency thinking for weather, so the operational side of :kw1 stays invisible.', ['kw1' => $kw(5)]),
        ]);

        $best = self::p([
            __('Best time to consider this style: seasonality shifts vegetation, water, and wildlife distribution. We will recommend months based on what you want to feel: green-season drama, dry-season clarity, or shoulder-season quiet.', []),
        ]);

        $why = self::p([
            __('Why Highlanders Nature Trails: we specialise in East Africa, speak plainly about trade-offs, and build safaris as relationships. Your style page becomes a conversation starter, not a contract written in stone.', []),
        ]);

        $conclusion = self::p([
            __('Next step: if :title matches your imagination, open the planner with this style selected. We will respond with a tailored proposal and a clear path from “interested” to “confirmed.”', ['title' => $title]),
        ]);

        $highlightCards = [
            ['title' => __('Wildlife windows'), 'body' => __('Routing and timing aligned with animal activity and softer light.')],
            ['title' => __('Comfort'), 'body' => __('Lodging choices matched to your pace and budget band.')],
            ['title' => __('Clarity'), 'body' => __('Expectations explained early, especially for families and first-time visitors.')],
            ['title' => __('Tailoring'), 'body' => __('Custom safari packages built from this style as a foundation.')],
        ];

        $quickFacts = [
            ['label' => __('Style'), 'value' => $title],
            ['label' => __('Typical duration'), 'value' => $duration],
            ['label' => __('Focus'), 'value' => __('Kenya & East Africa wildlife')],
            ['label' => __('Planning'), 'value' => __('Private tailoring available')],
        ];

        $perfectFor = [
            __('First-time safari travellers who want structure with flexibility'),
            __('Families needing clear pacing and safety framing'),
            __('Returning guests refining a favourite rhythm'),
        ];

        $faqItems = [
            [
                'question' => __('Is this safari style suitable for families?'),
                'answer' => __('Yes. When we know ages and interests, we adjust driving lengths, meal timing, and lodge choices so :title stays enjoyable for everyone.', ['title' => $title]),
            ],
            [
                'question' => __('How does a “style” become a real itinerary?'),
                'answer' => __('You share dates, party size, and budget band; we translate the style into named parks, camps, and transfers, with written inclusions and clear next steps.', []),
            ],
            [
                'question' => __('Can we combine this with mountain trekking or beach time?'),
                'answer' => __('Often yes. We sequence regions to manage climate shifts and recovery days so the whole journey feels coherent.', []),
            ],
            [
                'question' => __('What should I pack?'),
                'answer' => __('Layers, neutral tones, binoculars, sunscreen, and a brimmed hat. We send a tailored checklist after booking.', []),
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
            ], $faqItems),
        ];

        $blob = $intro.$overview.$pacing.$wildlife.$lodging.$logistics.$best.$why.$conclusion;
        $wc = str_word_count(strip_tags(str_replace(["\xc2\xa0"], ' ', $blob)));

        return [
            'introduction' => $intro,
            'overview' => $overview,
            'pacing' => $pacing,
            'wildlife' => $wildlife,
            'lodging' => $lodging,
            'logistics' => $logistics,
            'best_time' => $best,
            'why_highlanders' => $why,
            'conclusion' => $conclusion,
            'highlight_cards' => $highlightCards,
            'quick_facts' => $quickFacts,
            'perfect_for' => $perfectFor,
            'faq' => $faqItems,
            'faq_json_ld' => $jsonLd,
            'suggested_meta_description' => Str::limit(strip_tags(__('Plan :title, :duration. Kenya safari specialists at Highlanders Nature Trails.', [
                'title' => $title,
                'duration' => $duration,
            ])), 158),
            'estimated_word_count' => $wc,
        ];
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private static function p(array $paragraphs): string
    {
        $out = '';
        foreach ($paragraphs as $text) {
            $out .= '<p class="mt-4 text-[1.05rem] leading-[1.8] text-ink/90 sm:text-lg">'.e($text).'</p>';
        }

        return $out;
    }
}
