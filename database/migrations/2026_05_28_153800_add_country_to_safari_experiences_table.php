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
            DB::statement('ALTER TABLE safari_experiences ADD COLUMN country VARCHAR(120) NULL');

            return;
        }

        Schema::table('safari_experiences', function (Blueprint $table): void {
            $table->string('country', 120)->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('safari_experiences', function (Blueprint $table): void {
            $table->dropColumn('country');
        });
    }
};
