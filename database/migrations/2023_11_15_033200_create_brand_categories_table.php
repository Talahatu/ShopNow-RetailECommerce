<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("brand_id");
            $table->unsignedBigInteger("category_id");
            $table->foreign("brand_id")->references("id")->on("brands");
            $table->foreign("category_id")->references("id")->on("categories");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_categories');
    }
}
