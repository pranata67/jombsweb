<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBtbtelIskerjakanToPermohonan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->boolean('isKerjakan_petugas_bt')->default(false);
            $table->boolean('isKerjakan_petugas_btel')->default(false);
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
            $table->dropColumn('isKerjakan_petugas_bt');
            $table->dropColumn('isKerjakan_petugas_btel');
        });
    }
}
