<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Ramsey\Uuid\v1;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("brand_id");
            $table->unsignedBigInteger("shop_id");
            $table->string("name");
            $table->string("description");
            $table->double("weight");
            $table->integer("stock");
            $table->integer("price");
            $table->string("SKU");
            $table->enum("status", ["live", "out of stock", "problem", "archive"]);
            $table->double("rating", null, null, true)->default(0);
            $table->foreign("category_id")->references("id")->on("categories");
            $table->foreign("shop_id")->references("id")->on("shops");
            $table->foreign("brand_id")->references("id")->on("brands");
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
        Schema::dropIfExists('products');
    }
}
