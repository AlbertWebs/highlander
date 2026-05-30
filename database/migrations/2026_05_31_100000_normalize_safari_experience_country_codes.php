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

        $map = [
            'kenya' => 'kenya',
            'tanzania' => 'tanzania',
            'uganda' => 'uganda',
        ];

        foreach (DB::table('safari_experiences')->whereNotNull('country')->get(['id', 'country']) as $row) {
            $code = strtolower(trim((string) $row->country));
            if (isset($map[$code])) {
                DB::table('safari_experiences')->where('id', $row->id)->update(['country' => $map[$code]]);
            }
        }
    }

    public function down(): void
    {
        // Non-destructive normalization.
    }
};
