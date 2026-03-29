<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('featured_media_type', 16)->default('image')->after('image');
            $table->text('featured_video_url')->nullable()->after('featured_media_type');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['featured_media_type', 'featured_video_url']);
        });
    }
};
