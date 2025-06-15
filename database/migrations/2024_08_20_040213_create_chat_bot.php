<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatBot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_bot', function (Blueprint $table) {
            $table->unsignedBigInteger('id_chat_bot')->comment('phone number');
            $table->string('status_chat')->nullable();
            $table->date('tanggal_chat');
            $table->time('jam_chat')->nullable();
            $table->boolean('status_akun')->comment('true{akun aktif}, false{akun nonaktif}');
            $table->string('status_chat_sebelumnya')->nullable();
            $table->timestamps();

            $table->primary('id_chat_bot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_bot');
    }
}
