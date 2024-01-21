<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_content', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chat_id");
            $table->dateTime("date");
            $table->text("content");
            $table->foreign("chat_id")->references("id")->on("chats");
            $table->timestamps();
            $table->enum("sender", ["customer", 'seller']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_content');
    }
}
