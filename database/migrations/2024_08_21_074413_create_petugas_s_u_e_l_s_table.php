<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetugasSUELSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petugas_su_el', function (Blueprint $table) {
            $table->id('id_petugas_su_el');
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
        Schema::dropIfExists('petugas_su_el');
    }
}
