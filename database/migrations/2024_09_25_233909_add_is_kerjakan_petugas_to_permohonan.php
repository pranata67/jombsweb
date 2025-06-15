<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsKerjakanPetugasToPermohonan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->boolean('isKerjakan_petugas_verifikator')->default(false);
            $table->boolean('isKerjakan_petugas_pemetaan')->default(false);
            $table->boolean('isKerjakan_petugas_lapangan')->default(false);
            $table->boolean('isKerjakan_petugas_suel')->default(false);
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
            $table->dropColumn('isKerjakan_petugas_verifikator');
            $table->dropColumn('isKerjakan_petugas_pemetaan');
            $table->dropColumn('isKerjakan_petugas_lapangan');
            $table->dropColumn('isKerjakan_petugas_suel');
        });
    }
}
