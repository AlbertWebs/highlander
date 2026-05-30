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

        foreach (DB::table('safari_experiences')->whereNotNull('country')->get(['id', 'country']) as $row) {
            $normalized = strtolower(trim((string) $row->country));
            if ($normalized !== '' && $normalized !== (string) $row->country) {
                DB::table('safari_experiences')->where('id', $row->id)->update(['country' => $normalized]);
            }
        }
    }

    public function down(): void
    {
        // Non-destructive normalization; no rollback.
    }
};
