<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group', function (Blueprint $table) {
            $table->id();
            $table->string('name','255');
            $table->unsignedBigInteger('category')->nullable();;
            $table->string('type','50');
            $table->string('join_token','50')->nullable();
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
        Schema::dropIfExists('chat_group');
    }
}
