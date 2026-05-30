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
            $table->string('country', 32)->nullable()->after('sort_order')->index();
        });

        $this->backfillCountries();
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }

    private function backfillCountries(): void
    {
        $rules = [
            'uganda' => [
                'uganda', 'bwindi', 'murchison', 'queen elizabeth', 'gorilla', 'kibale', 'entebbe',
            ],
            'tanzania' => [
                'tanzania', 'serengeti', 'ngorongoro', 'kilimanjaro', 'zanzibar', 'arusha', 'meru',
                'ruaha', 'selous', 'tarangire', 'manyara', 'uhuru',
            ],
            'kenya' => [
                'kenya', 'masai mara', 'maasai mara', 'amboseli', 'tsavo', 'nakuru', 'samburu',
                'mount kenya', 'naromoru', 'sirimon', 'chogoria', 'kamweti', 'timau', 'burguret',
                'thingira', 'tcv cultural', 'nairobi', 'laikipia', 'mara', 'diani', 'watamu',
            ],
        ];

        foreach (DB::table('tours')->get(['id', 'title', 'slug', 'description']) as $tour) {
            $haystack = strtolower(
                (string) $tour->title.' '.(string) $tour->slug.' '.(string) ($tour->description ?? '')
            );

            $country = null;
            foreach ($rules as $code => $needles) {
                foreach ($needles as $needle) {
                    if (str_contains($haystack, $needle)) {
                        $country = $code;
                        break 2;
                    }
                }
            }

            if ($country !== null) {
                DB::table('tours')->where('id', $tour->id)->update(['country' => $country]);
            }
        }
    }
};
