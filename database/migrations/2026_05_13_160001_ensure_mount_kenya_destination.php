<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $slug = 'mount-kenya';
        $description = '<p>Africa’s second-highest peak: glacier-carved valleys, afro-alpine flora, and trekking routes including Sirimon, Naromoru, Chogoria, Burguret, and Kamweti. We also weave in highland culture and farm experiences around the massif.</p>';

        $row = DB::table('destinations')->where('slug', $slug)->first();

        if ($row === null) {
            DB::table('destinations')->insert([
                'name' => 'Mount Kenya',
                'slug' => $slug,
                'description' => $description,
                'image' => null,
                'is_active' => true,
                'sort_order' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return;
        }

        DB::table('destinations')->where('slug', $slug)->update([
            'sort_order' => 100,
            'is_active' => true,
            'updated_at' => $now,
            'description' => filled(trim((string) ($row->description ?? '')))
                ? $row->description
                : $description,
        ]);
    }

    public function down(): void
    {
        // Leave row in place; only a net-new insert would be removed in strict rollbacks.
    }
};
