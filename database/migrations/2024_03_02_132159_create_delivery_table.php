<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("courier_id");
            $table->dateTime("start_date");
            $table->string("resi");
            $table->enum("status", ["done", "progress", "new"]);
            $table->timestamps();
            $table->dateTime("pickup_date")->nullable();
            $table->dateTime("arrive_date")->nullable();
            $table->string("proofImage")->nullable();
            $table->double("feeAssigned")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery');
    }
}
