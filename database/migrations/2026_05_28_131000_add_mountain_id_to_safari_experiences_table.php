<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // Older SQLite builds on some shared hosts fail Laravel's table introspection
            // during Schema::table() alters. Use raw SQL for compatibility.
            DB::statement('ALTER TABLE safari_experiences ADD COLUMN mountain_id INTEGER NULL');

            return;
        }

        Schema::table('safari_experiences', function (Blueprint $table): void {
            $table->foreignId('mountain_id')
                ->nullable()
                ->after('duration')
                ->constrained('mountains')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // SQLite cannot reliably drop columns across old versions.
            return;
        }

        Schema::table('safari_experiences', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('mountain_id');
        });
    }
};
