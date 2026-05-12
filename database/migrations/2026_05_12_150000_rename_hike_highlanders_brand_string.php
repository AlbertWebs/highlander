<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const FROM = 'Hike Highlanders Nature Trails & Safaris';

    private const TO = 'Highlanders Nature Trails & Safaris';

    /**
     * @param  array<int, string>  $columns
     */
    private function replaceInTable(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            $rows = DB::table($table)->select(['id', $column])->get();
            foreach ($rows as $row) {
                $val = $row->{$column} ?? null;
                if (! is_string($val) || ! str_contains($val, self::FROM)) {
                    continue;
                }
                DB::table($table)->where('id', $row->id)->update([
                    $column => str_replace(self::FROM, self::TO, $val),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function up(): void
    {
        $this->replaceInTable('about_page_settings', [
            'hero_title', 'hero_subtitle', 'intro_heading', 'intro_paragraph_1', 'intro_paragraph_2',
            'fleet_heading', 'fleet_body', 'team_heading', 'team_body', 'safety_heading', 'safety_body',
            'core_values_section_title', 'sustainability_section_title', 'testimonials_section_title',
            'cta_heading', 'cta_body', 'intro_cta_label', 'cta_button_label',
        ]);

        $this->replaceInTable('about_vision_mission_cards', ['title', 'body']);
        $this->replaceInTable('about_core_values', ['title', 'description']);
        $this->replaceInTable('about_fleet_subsections', ['title', 'body']);
        $this->replaceInTable('about_sustainability_items', ['title', 'description']);
        $this->replaceInTable('about_safety_points', ['point_text']);
        $this->replaceInTable('about_team_roles', ['label']);
    }

    public function down(): void
    {
        // Not reversible.
    }
};
