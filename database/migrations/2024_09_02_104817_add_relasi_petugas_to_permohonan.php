<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiPetugasToPermohonan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->bigInteger('petugas_lapang_id')->nullable();
            $table->bigInteger('petugas_pemetaan_id')->nullable();
            $table->bigInteger('petugas_suel_id')->nullable();
            $table->bigInteger('petugas_btel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropColumn('petugas_lapang_id');
            $table->dropColumn('petugas_pemetaan_id');
            $table->dropColumn('petugas_suel_id');
            $table->dropColumn('petugas_btel_id');
        });
    }
}
