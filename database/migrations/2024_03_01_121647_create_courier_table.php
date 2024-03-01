<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shop_id");
            $table->string("name");
            $table->string("username");
            $table->string("password");
            $table->foreign("shop_id")->references("id")->on("shops");
            $table->integer("operationalFee");
            $table->string("email");
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
        Schema::dropIfExists('courier');
    }
}
