<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierFeeHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_fee_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("courier_id");
            $table->integer("nominal")->nullable();
            $table->text("description");
            $table->enum("type", ["add", "withdraw"]);
            $table->timestamps();
            $table->foreign("courier_id")->references("id")->on("courier");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courier_fee_histories');
    }
}
