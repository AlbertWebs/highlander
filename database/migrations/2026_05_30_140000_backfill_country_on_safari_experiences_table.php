<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('safari_experiences') || ! Schema::hasColumn('safari_experiences', 'country')) {
            return;
        }

        $rules = [
            'uganda' => [
                'uganda', 'bwindi', 'murchison', 'queen elizabeth', 'gorilla', 'kibale', 'entebbe',
            ],
            'tanzania' => [
                'tanzania', 'serengeti', 'ngorongoro', 'kilimanjaro', 'zanzibar', 'arusha', 'meru',
                'ruaha', 'selous', 'tarangire', 'manyara',
            ],
            'kenya' => [
                'kenya', 'masai mara', 'maasai mara', 'amboseli', 'tsavo', 'nakuru', 'samburu',
                'mount kenya', 'naromoru', 'sirimon', 'chogoria', 'big five', 'mara', 'laikipia',
            ],
        ];

        foreach (DB::table('safari_experiences')->get(['id', 'title', 'slug', 'description', 'country']) as $row) {
            if (filled($row->country) && in_array(strtolower((string) $row->country), ['kenya', 'tanzania', 'uganda'], true)) {
                continue;
            }

            $haystack = strtolower(
                (string) $row->title.' '.(string) $row->slug.' '.(string) ($row->description ?? '')
            );

            $country = null;
            foreach ($rules as $code => $needles) {
                foreach ($needles as $needle) {
                    if (str_contains($haystack, $needle)) {
                        $country = $code;
                        break 2;
                    }
                }
            }

            if ($country !== null) {
                DB::table('safari_experiences')->where('id', $row->id)->update(['country' => $country]);
            }
        }
    }

    public function down(): void
    {
        // Non-destructive backfill; no rollback.
    }
};
