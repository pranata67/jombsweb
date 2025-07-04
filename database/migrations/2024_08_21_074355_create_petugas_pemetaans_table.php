<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasPemetaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petugas_pemetaan', function (Blueprint $table) {
            $table->id('id_petugas_pemetaan');
            $table->string('nama_lengkap', 255);
            $table->string('no_telepon', 50);
            $table->string('username', 50);
            $table->string('password', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petugas_pemetaan');
    }
}
