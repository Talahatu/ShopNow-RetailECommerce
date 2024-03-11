<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Brand::factory()->count(100)->create();
        $brands = ["Fantech", "Sony", "Samsung", "Aqua", "Le Minerale", "LPG", "Omicko", "Panasonic", "Toshiba", "Mitsubishi", "Yamaha", "Sarong", "Kusuma", "Bali Batik", "IKEA", "Lio Collection", "Yonex", "Hitachi", "DEKO", "Makita", "Bosch", "Adidas", "Nike", "AlphaTauri", "Versace", "Hermes", "Tom Ford", "Lainnya"];
        DB::table("brands")->insert($brands);
        BrandCategory::factory()->count(100)->create();
    }
}
