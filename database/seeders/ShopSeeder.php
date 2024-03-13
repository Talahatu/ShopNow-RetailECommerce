<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Shop::factory()->count(2)->create();

        DB::table("shops")->insert([
            [
                "1", "Panasanik", "08122344543", "Siji Tunggal Kopi Rungkut Lor, Surabaya", "-7.326770", "112.769090", "shop_1699341657.JPG", 0, 0
            ],
            [
                "2", "Sany", "08122377993", "Tenggilis Mejoyo Selatan No 29, Surabaya", "-7.321140", "112.759770", "shop_1699341657.JPG", 0, 0
            ],
        ]);
    }
}
