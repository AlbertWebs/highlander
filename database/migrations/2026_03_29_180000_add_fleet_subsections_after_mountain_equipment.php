<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add two fleet/equipment pointers after “Mountain Equipment” (existing seeded DBs).
     */
    public function up(): void
    {
        $now = now();
        $rows = [
            [
                'title' => 'Communications & Navigation',
                'body' => 'GPS, radios, and satellite options where routes require dependable contact and positioning in remote areas.',
                'sort_order' => 4,
            ],
            [
                'title' => 'Spare Parts & Recovery',
                'body' => 'Tools, spare wheels, jacks, and recovery gear carried on extended departures so we’re prepared off the beaten track.',
                'sort_order' => 5,
            ],
        ];

        foreach ($rows as $row) {
            $exists = DB::table('about_fleet_subsections')
                ->where('title', $row['title'])
                ->exists();

            if (! $exists) {
                DB::table('about_fleet_subsections')->insert([
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('about_fleet_subsections')->whereIn('title', [
            'Communications & Navigation',
            'Spare Parts & Recovery',
        ])->delete();
    }
};
