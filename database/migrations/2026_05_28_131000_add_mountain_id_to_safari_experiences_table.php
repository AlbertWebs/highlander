<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
        Schema::table('safari_experiences', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('mountain_id');
        });
    }
};
