<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $slug = 'mount-kilimanjaro';
        $description = '<p>Tanzania\'s iconic freestanding volcano: rainforest slopes, high alpine desert, and glacier-crowned Uhuru Peak. We publish route-based programmes and tailor pacing to your dates and acclimatisation needs.</p>';

        $row = DB::table('mountains')->where('slug', $slug)->first();

        if ($row === null) {
            DB::table('mountains')->insert([
                'name' => 'Mount Kilimanjaro',
                'slug' => $slug,
                'description' => $description,
                'elevation_m' => 5895,
                'image' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return;
        }

        DB::table('mountains')->where('slug', $slug)->update([
            'is_active' => true,
            'elevation_m' => 5895,
            'updated_at' => $now,
            'description' => filled(trim(strip_tags((string) ($row->description ?? ''))))
                ? $row->description
                : $description,
        ]);
    }

    public function down(): void
    {
        // Leave row in place.
    }
};
