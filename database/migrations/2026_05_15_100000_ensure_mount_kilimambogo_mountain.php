<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $slug = 'mount-kilimambogo';
        $description = '<p>A prominent volcanic outlier in central Kenya, rising above the Athi plains near Ol Donyo Sabuk. Shorter summit days and big-sky views make it a rewarding add-on to Nairobi or Mount Kenya itineraries.</p>';

        $row = DB::table('mountains')->where('slug', $slug)->first();

        if ($row === null) {
            DB::table('mountains')->insert([
                'name' => 'Mount Kilimambogo',
                'slug' => $slug,
                'description' => $description,
                'elevation_m' => 2144,
                'image' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return;
        }

        DB::table('mountains')->where('slug', $slug)->update([
            'is_active' => true,
            'elevation_m' => 2144,
            'updated_at' => $now,
            'name' => 'Mount Kilimambogo',
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
