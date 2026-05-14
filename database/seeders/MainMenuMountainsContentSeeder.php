<?php

namespace Database\Seeders;

use App\Models\Mountain;
use Illuminate\Database\Seeder;

/**
 * Rich copy and elevations for peaks listed in config/navigation.php (mountains_main_menu_slugs).
 * Safe to re-run: updateOrCreate by slug.
 */
class MainMenuMountainsContentSeeder extends Seeder
{
    public function run(): void
    {
        $peaks = [
            'mount-kenya' => [
                'name' => 'Mount Kenya',
                'elevation_m' => 5199,
                'description' => <<<'HTML'
<p>Mount Kenya is the highest mountain in Kenya and the second-highest peak in Africa after Kilimanjaro. The massif is a UNESCO World Heritage Site: glacier-sculpted ridges, tarns, and afro-alpine moorland dotted with giant lobelias and groundsels.</p>
<p>Classic trekking corridors include Sirimon, Naromoru, and Chogoria, with longer variants such as Burguret and Kamweti for quieter forest approaches. Summit nights target Batian or Nelion for technical climbers, or Point Lenana (4,985 m) for a challenging non-technical sunrise finish with sweeping views across the Rift.</p>
<p>We combine multi-day circuits with highland culture, farm visits, and conservancy wildlife where it fits your dates, so the mountain feels connected to the wider central Kenya landscape.</p>
HTML,
                'is_active' => true,
            ],
            'mount-kilimanjaro' => [
                'name' => 'Mount Kilimanjaro',
                'elevation_m' => 5895,
                'description' => <<<'HTML'
<p>Kilimanjaro is a freestanding stratovolcano in northern Tanzania and the tallest peak in Africa at 5,895 metres. Its climb is non-technical on trekking routes, but altitude and weather still demand respect, solid pacing, and experienced mountain support.</p>
<p>From rainforest and heath to alpine desert and summit crater, each ecological band changes within days. Popular approaches include Machame, Lemosho, Marangu, Rongai, and Umbwe; we help match route length, acclimatisation profile, and season to your fitness and schedule.</p>
<p>Summit night crosses scree and often ice-crusted trails toward Uhuru Peak. With the right preparation, Kilimanjaro remains one of the world’s most rewarding high-altitude objectives for determined walkers.</p>
HTML,
                'is_active' => true,
            ],
            'mount-kilimambogo' => [
                'name' => 'Mount Kilimambogo',
                'elevation_m' => 2144,
                'description' => <<<'HTML'
<p>Mount Kilimambogo (Ol Donyo Sabuk) rises above the Athi-Kapiti plains northeast of Nairobi. At just over 2,100 metres it is a compact day or overnight objective: forest trails, open ridges, and wide horizons toward the Aberdares and Mount Kenya on clear days.</p>
<p>The mountain sits within Ol Donyo Sabuk National Park, known for buffalo herds and birdlife. It works well as a first Kenyan summit, a family-friendly hike with sensible pacing, or a scenic add-on before longer Mount Kenya or safari legs.</p>
<p>Expect early starts, steady climbing, and big-sky viewpoints rather than high-alpine exposure. We keep groups small and align support with park regulations and seasonal trail conditions.</p>
HTML,
                'is_active' => true,
            ],
            'mount-meru' => [
                'name' => 'Mount Meru',
                'elevation_m' => 4566,
                'description' => <<<'HTML'
<p>Mount Meru towers over Arusha National Park in Tanzania and, at 4,566 metres, is often called Kilimanjaro’s little sister. A Meru ascent is a serious mountain journey in its own right: rainforest, giant heather, rocky alpine ridges, and a dramatic caldera rim before the final push to Socialist Peak.</p>
<p>Many climbers use Meru as a structured acclimatisation week before Kilimanjaro, but the route stands alone for scenery: giraffe and buffalo sometimes graze near the lower trail, and the ash cone inside the crater is unforgettable in morning light.</p>
<p>Summit day is steep and cold, with armed rangers accompanying sections inside the park. We plan realistic stage lengths, hut or camping logistics, and safety margins so you arrive on Kili or home with confidence after a complete mountain experience.</p>
HTML,
                'is_active' => true,
            ],
        ];

        foreach ($peaks as $slug => $attributes) {
            Mountain::query()->updateOrCreate(
                ['slug' => $slug],
                $attributes
            );
        }
    }
}
