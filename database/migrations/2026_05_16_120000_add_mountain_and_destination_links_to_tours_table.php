<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('mountain_id')->nullable()->after('nav_bucket')->constrained('mountains')->nullOnDelete();
            $table->foreignId('destination_id')->nullable()->after('mountain_id')->constrained('destinations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('destination_id');
            $table->dropConstrainedForeignId('mountain_id');
        });
    }
};
