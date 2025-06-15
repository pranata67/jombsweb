<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableKerjakanPermohonanLapang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kerjakan_permohonan_lapang', function (Blueprint $table) {
            $table->string('catatan_lapangan', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kerjakan_permohonan_lapang', function (Blueprint $table) {
            $table->dropColumn('catatan_lapangan');
        });
    }
}
