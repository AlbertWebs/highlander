<?php

/**
 * Primary site navigation curation (see resources/views/partials/site-header.blade.php).
 *
 * Only these mountain hub slugs appear under the main "Mountains" dropdown, in order.
 * Individual treks and flights belong on each mountain's page or under Safari — not here.
 * Add a slug after creating the hub row in admin (or via seeders).
 */
return [
    'mountains_main_menu_slugs' => [
        'mount-kenya',
        'mount-kilimanjaro',
        'mount-kilimambogo',
        'mount-meru',
    ],
];
