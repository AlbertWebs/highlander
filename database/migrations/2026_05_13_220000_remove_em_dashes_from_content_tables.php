<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const EM_DASH = "\u{2014}";

    private const REPLACEMENT = ', ';

    /**
     * Replace Unicode em dash (U+2014) in CMS and user-facing text columns.
     * Uses comma + space for consistency with the earlier about-page em dash migration.
     *
     * @param  array<int, string>  $columns
     */
    private function replaceInTable(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $hasUpdatedAt = Schema::hasColumn($table, 'updated_at');

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                continue;
            }

            $rows = DB::table($table)->select(['id', $column])->get();
            foreach ($rows as $row) {
                $val = $row->{$column} ?? null;
                if (! is_string($val) || ! str_contains($val, self::EM_DASH)) {
                    continue;
                }
                $updated = str_replace(self::EM_DASH, self::REPLACEMENT, $val);
                $payload = [$column => $updated];
                if ($hasUpdatedAt) {
                    $payload['updated_at'] = now();
                }
                DB::table($table)->where('id', $row->id)->update($payload);
            }
        }
    }

    public function up(): void
    {
        $this->replaceInTable('tours', ['title', 'description', 'meta_title', 'meta_description']);
        $this->replaceInTable('tour_itinerary_days', ['title', 'body']);
        $this->replaceInTable('articles', ['title', 'excerpt', 'body', 'meta_title', 'meta_description']);
        $this->replaceInTable('destinations', ['name', 'description']);
        $this->replaceInTable('mountains', ['name', 'description']);
        $this->replaceInTable('safari_experiences', ['title', 'description']);
        $this->replaceInTable('testimonials', ['name', 'role', 'quote']);
        $this->replaceInTable('seo_metas', ['meta_title', 'meta_description', 'meta_keywords']);
        $this->replaceInTable('site_settings', ['value']);
        $this->replaceInTable('gallery_items', ['title', 'alt']);
        $this->replaceInTable('gallery_categories', ['name']);
        $this->replaceInTable('contact_messages', ['name', 'email', 'subject', 'message']);
        $this->replaceInTable('bookings', ['name', 'email', 'phone', 'message']);
        $this->replaceInTable('safari_requests', [
            'full_name', 'email', 'phone', 'country', 'contact_method', 'children_ages',
            'group_type', 'accommodation_type', 'room_type', 'transport_type', 'budget_range',
            'special_requests', 'admin_notes',
        ]);
        $this->replaceInTable('activity_logs', ['action', 'subject_type', 'description']);
        $this->replaceInTable('media_files', ['original_name', 'alt']);

        $this->replaceInTable('about_page_settings', [
            'hero_title', 'hero_subtitle', 'intro_heading', 'intro_paragraph_1', 'intro_paragraph_2',
            'fleet_heading', 'fleet_body', 'team_heading', 'team_body', 'safety_heading', 'safety_body',
            'core_values_section_title', 'sustainability_section_title', 'testimonials_section_title',
            'cta_heading', 'cta_body', 'intro_cta_label', 'cta_button_label',
        ]);
        $this->replaceInTable('about_vision_mission_cards', ['title', 'body']);
        $this->replaceInTable('about_core_values', ['title', 'description']);
        $this->replaceInTable('about_fleet_images', ['caption']);
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
