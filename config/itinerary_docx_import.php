<?php

/**
 * Map public/itineraries/*.docx basenames to canonical Tour titles.
 * Keys are lower-cased basenames for case-insensitive lookup on Windows/Linux.
 *
 * Slugs are always Str::slug($title) so URLs match existing brochure tours where titles match
 * Database\Seeders\MountKenyaItinerarySeeder and Database\Seeders\MountKenyaExtendedItinerarySeeder.
 */
return [
    'skip_basenames_lower' => [
        'mt kenya introduction.docx',
    ],

    /**
     * @var array<string, array{title: string, sort_order?: int}>
     */
    'files' => [
        '3 days naromoru- naromoru route wps office.docx' => [
            'title' => '3 Days Naromoru - Naromoru Route',
            'sort_order' => 20,
        ],
        '4 days sirimon- naromoru route wps office.docx' => [
            'title' => '4 Days Sirimon – Naromoru Route',
            'sort_order' => 21,
        ],
        '4 days sirimon- sirimon routewps office.docx' => [
            'title' => '4 Days Sirimon - Sirimon Route',
            'sort_order' => 22,
        ],
        '5 days burguret-wps office.docx' => [
            'title' => '5 Days Burguret Route',
            'sort_order' => 23,
        ],
        '5 days chogoria- chogoria wps office.docx' => [
            'title' => '5 Days Chogoria - Chogoria Route',
            'sort_order' => 24,
        ],
        '5 days chogoria-wps office.docx' => [
            'title' => '5 Days Chogoria - Chogoria Route',
            'sort_order' => 24,
        ],
        '5 days kamweti-wps office.docx' => [
            'title' => '5 Days Kamweti Route',
            'sort_order' => 36,
        ],
        '5 days timau ro-wps office.docx' => [
            'title' => '5 Days Timau Route',
            'sort_order' => 37,
        ],
        '7 days mount ke- circuit wps office.docx' => [
            'title' => '7 Days Mount Kenya Peaks Circuit',
            'sort_order' => 38,
        ],
        '7 days naromoru- technical climbing wps office.docx' => [
            'title' => '7 Days Naromoru Route – Technical Climbing Expedition',
            'sort_order' => 39,
        ],
        'thingira cultural festival.docx' => [
            'title' => 'Thingira Cultural Festival',
            'sort_order' => 210,
        ],
    ],
];
