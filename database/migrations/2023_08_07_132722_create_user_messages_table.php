<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('for')->nullable();
            $table->ENUM('type',['text','image','video','audio','file']);
            $table->tinyInteger('was_replied')->default(0);
            $table->timestamp('seen_at')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('for')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_messages');
    }
}
