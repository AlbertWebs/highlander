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
            $table->string('nav_bucket', 32)->default('safari')->after('sort_order');
        });

        $this->backfillNavBuckets();
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('nav_bucket');
        });
    }

    private function backfillNavBuckets(): void
    {
        DB::table('tours')->update(['nav_bucket' => 'safari']);

        $mountainSafariSlugs = [
            '3-days-naromoru-naromoru-route',
            '4-days-sirimon-naromoru-route',
            '4-days-sirimon-sirimon-route',
            '5-days-burguret-route',
            '5-days-chogoria-chogoria-route',
            '5-days-sirimon-chogoria-route',
            '5-days-kamweti-route',
            '5-days-timau-route',
            '7-days-mount-kenya-peaks-circuit',
            '7-days-naromoru-route-technical-climbing-expedition',
            'kilimanjaro-lemosho-route',
        ];

        DB::table('tours')->whereIn('slug', $mountainSafariSlugs)->update(['nav_bucket' => 'mountain_safari']);

        DB::table('tours')->whereIn('slug', [
            't-c-v-cultural-and-farm-experience',
            'thingira-cultural-festival',
        ])->update(['nav_bucket' => 'explore_africa']);
    }
};
