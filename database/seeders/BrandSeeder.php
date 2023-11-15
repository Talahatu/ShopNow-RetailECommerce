<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandCategory;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::factory()->count(100)->create();
        BrandCategory::factory()->count(100)->create();
    }
}
