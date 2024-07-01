<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shop_id");
            $table->integer("income")->nullable();
            $table->date("date");
            $table->string("metode")->nullable();
            $table->foreign("shop_id")->references("id")->on("shops");
            $table->timestamps();
            $table->integer("withdraw")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financials_history');
    }
}
