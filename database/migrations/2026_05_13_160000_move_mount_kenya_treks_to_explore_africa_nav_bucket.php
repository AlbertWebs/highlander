<?php

use App\Models\Tour;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tours')
            ->whereIn('slug', Tour::MOUNT_KENYA_TREK_SLUGS)
            ->update(['nav_bucket' => Tour::NAV_EXPLORE_AFRICA]);
    }

    public function down(): void
    {
        DB::table('tours')
            ->whereIn('slug', Tour::MOUNT_KENYA_TREK_SLUGS)
            ->update(['nav_bucket' => Tour::NAV_MOUNTAIN_SAFARI]);
    }
};
