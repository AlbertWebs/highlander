<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('nav_safari')->default(false)->after('nav_bucket');
            $table->boolean('nav_mountain_safari')->default(false)->after('nav_safari');
            $table->boolean('nav_explore_africa')->default(false)->after('nav_mountain_safari');
        });

        DB::table('tours')->where('nav_bucket', 'safari')->update([
            'nav_safari' => true,
            'nav_mountain_safari' => false,
            'nav_explore_africa' => false,
        ]);
        DB::table('tours')->where('nav_bucket', 'mountain_safari')->update([
            'nav_safari' => false,
            'nav_mountain_safari' => true,
            'nav_explore_africa' => false,
        ]);
        DB::table('tours')->where('nav_bucket', 'explore_africa')->update([
            'nav_safari' => false,
            'nav_mountain_safari' => false,
            'nav_explore_africa' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['nav_safari', 'nav_mountain_safari', 'nav_explore_africa']);
        });
    }
};
