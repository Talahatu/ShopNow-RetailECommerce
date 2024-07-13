<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("shop_id");
            $table->dateTime("order_date");
            $table->enum("orderStatus", ['new', 'accepted', 'sent', "done", "cancel"]);
            $table->string("destination_address");
            $table->string("destination_latitude");
            $table->string("destination_longitude");
            $table->enum("payment_method", ["saldo", "cod"]);
            $table->enum("payment_status", ["onhold", "release"]);
            $table->date("payment_release_date")->nullable();
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("shop_id")->references("id")->on("shops");
            $table->timestamps();
            $table->integer("total");
            $table->integer("subtotal");
            $table->integer("shippingFee");
            $table->string("orderID");
            $table->double("distance");
            $table->dateTime("accept_date")->nullable();
            $table->dateTime("cancel_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
