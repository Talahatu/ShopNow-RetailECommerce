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
                "user_id" => "1", "name" => "Panasanik", "phoneNumber" => "08122344543", "address" => "Siji Tunggal Kopi Rungkut Lor, Surabaya", "lat" => "-7.293700", "long" => " 112.755730", "logoImage" => "shop_1699341657.JPG", "saldo_release" => 0
            ],
            [
                "user_id" => "2",  "name" => "Sany", "phoneNumber" => "08122377993", "address" => "Tenggilis Mejoyo Selatan No 29, Surabaya", "lat" => "-7.321140", "long" => " 112.755730", "logoImage" => "shop_1699341657.JPG", "saldo_release" => 0
            ],
        ]);
    }
}
