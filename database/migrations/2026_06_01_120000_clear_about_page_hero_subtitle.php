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

        DB::table('about_page_settings')->update(['hero_subtitle' => null]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('about_page_settings')) {
            return;
        }

        $brand = 'Highlanders Nature Trails & Safaris';

        DB::table('about_page_settings')->update([
            'hero_subtitle' => <<<'HTML'
<p>We are passionate about mountains, adventure, and the wild beauty of East Africa. Whether you are summiting the peaks of Mount Kenya or Kilimanjaro, trekking off-the-beaten-path trails, or embarking on a safari across the region’s game reserves and national parks, we deliver exceptional, personalized experiences every step of the way.</p>
<p><strong>Backed by expert guides and a deep love for the outdoors, we ensure your journey is not just a trip, but a transformational adventure.</strong> From rugged mountain ascents to wildlife encounters, we have you covered.</p>
HTML,
        ]);
    }
};
