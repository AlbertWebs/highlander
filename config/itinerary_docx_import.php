<?php

/**
 * Map public/itineraries/*.docx basenames to canonical Tour titles.
 * Keys are lower-cased basenames for case-insensitive lookup on Windows/Linux.
 *
 * Slugs are always Str::slug($title) so URLs match existing brochure tours where titles match
 * Database\Seeders\MountKenyaItinerarySeeder and Database\Seeders\MountKenyaExtendedItinerarySeeder.
 *
 * nav_bucket: safari | mountain_safari | explore_africa (see App\Models\Tour::NAV_*).
 */
return [
    'skip_basenames_lower' => [
        'mt kenya introduction.docx',
    ],

    /**
     * @var array<string, array{title: string, sort_order?: int, nav_bucket?: string}>
     */
    'files' => [
        '3 days naromoru- naromoru route wps office.docx' => [
            'title' => '3 Days Naromoru - Naromoru Route',
            'sort_order' => 20,
            'nav_bucket' => 'mountain_safari',
        ],
        '4 days sirimon- naromoru route wps office.docx' => [
            'title' => '4 Days Sirimon – Naromoru Route',
            'sort_order' => 21,
            'nav_bucket' => 'mountain_safari',
        ],
        '4 days sirimon- sirimon routewps office.docx' => [
            'title' => '4 Days Sirimon - Sirimon Route',
            'sort_order' => 22,
            'nav_bucket' => 'mountain_safari',
        ],
        '5 days burguret-wps office.docx' => [
            'title' => '5 Days Burguret Route',
            'sort_order' => 23,
            'nav_bucket' => 'mountain_safari',
        ],
        '5 days chogoria- chogoria wps office.docx' => [
            'title' => '5 Days Chogoria - Chogoria Route',
            'sort_order' => 24,
            'nav_bucket' => 'mountain_safari',
        ],
        '5 days chogoria-wps office.docx' => [
            'title' => '5 Days Chogoria - Chogoria Route',
            'sort_order' => 24,
            'nav_bucket' => 'mountain_safari',
        ],
        '5 days kamweti-wps office.docx' => [
            'title' => '5 Days Kamweti Route',
            'sort_order' => 36,
            'nav_bucket' => 'mountain_safari',
        ],
        '5 days timau ro-wps office.docx' => [
            'title' => '5 Days Timau Route',
            'sort_order' => 37,
            'nav_bucket' => 'mountain_safari',
        ],
        '7 days mount ke- circuit wps office.docx' => [
            'title' => '7 Days Mount Kenya Peaks Circuit',
            'sort_order' => 38,
            'nav_bucket' => 'mountain_safari',
        ],
        '7 days naromoru- technical climbing wps office.docx' => [
            'title' => '7 Days Naromoru Route – Technical Climbing Expedition',
            'sort_order' => 39,
            'nav_bucket' => 'mountain_safari',
        ],
        'thingira cultural festival.docx' => [
            'title' => 'Thingira Cultural Festival',
            'sort_order' => 210,
            'nav_bucket' => 'explore_africa',
        ],
    ],
];
