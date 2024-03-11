<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Category::factory()->count(15)->create();

        $categories = ["Alat Rumah Tangga", "Buku", "Elekronik", "Mebel", "Pakaian Anak", "Pakaian Pria", "Pakaian Wanita", "Makanan", "Minuman", "Gaming", "Mainan Anak", "Alat Musik", "Handphone dan Tablet", "Kesehatan", "Kecantikan", "Komputer dan Laptop", "Hobi", "Peralatan Kantor dan Sekolah", "Olahraga", "Otomotif", "Pertukangan", "Perawatan Hewan", "Lainnya"];
        DB::table("categories")->insert($categories);
    }
}
