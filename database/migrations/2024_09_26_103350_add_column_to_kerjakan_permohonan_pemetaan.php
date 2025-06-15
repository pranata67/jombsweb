<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToKerjakanPermohonanPemetaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kerjakan_permohonan_pemetaan', function (Blueprint $table) {
            $table->string('petugas_pengukuran', 255)->nullable()->comment('{nama_petugas_lapang}');
            $table->integer('petugas_lapang_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kerjakan_permohonan_pemetaan', function (Blueprint $table) {
            $table->dropColumn('petugas_pengukuran');
            $table->dropColumn('petugas_lapang_id');
        });
    }
}
