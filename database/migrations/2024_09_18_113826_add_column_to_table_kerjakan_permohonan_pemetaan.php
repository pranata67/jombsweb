<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableKerjakanPermohonanPemetaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kerjakan_permohonan_pemetaan', function (Blueprint $table) {
            $table->string('catatan_pemetaan', 255)->nullable();
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
            $table->dropColumn('catatan_pemetaan');
        });
    }
}
