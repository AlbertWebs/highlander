<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use Illuminate\Database\Seeder;

class GalleryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Mt. Kilimanjaro Hikes', 'slug' => 'mt-kilimanjaro-hikes', 'sort_order' => 1],
            ['name' => 'Mt. Kenya Hikes', 'slug' => 'mt-kenya-hikes', 'sort_order' => 2],
            ['name' => 'Kenya Wildlife Safaris', 'slug' => 'kenya-wildlife-safaris', 'sort_order' => 3],
            ['name' => 'Mt. Kenya Rock Climbing', 'slug' => 'mt-kenya-rock-climbing', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            GalleryCategory::query()->updateOrCreate(
                ['slug' => $row['slug']],
                $row + ['is_active' => true]
            );
        }
    }
}
