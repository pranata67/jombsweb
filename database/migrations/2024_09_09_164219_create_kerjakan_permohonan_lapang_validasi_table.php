<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerjakanPermohonanLapangValidasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kerjakan_permohonan_lapang_validasi', function (Blueprint $table) {
            $table->id('id_kerjakan_permohonan_lapang_validasi');
            $table->bigInteger('permohonan_id');
            $table->bigInteger('user_id');
            $table->string('no_hak')->nullable();
            $table->string('kecamatan_id')->nullable();
            $table->string('desa_id')->nullable();
            $table->text('foto_lokasi')->nullable();
            $table->text('file_dwg')->nullable();
            $table->text('sket_gambar')->nullable();
            $table->text('txt_csv')->nullable();
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
        Schema::dropIfExists('kerjakan_permohonan_lapang_validasi');
    }
}
