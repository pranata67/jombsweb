<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerjakanPermohonanVerifikatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kerjakan_permohonan_verifikator', function (Blueprint $table) {
            $table->id('id_kerjakan_permohonan_verifikator');
            $table->bigInteger('permohonan_id');
            $table->bigInteger('user_id');
            $table->string('jenis_pemetaan');
            $table->string('petugas_pengukuran');
            $table->bigInteger('petugas_lapang_id');
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
        Schema::dropIfExists('kerjakan_permohonan_verifikator');
    }
}
