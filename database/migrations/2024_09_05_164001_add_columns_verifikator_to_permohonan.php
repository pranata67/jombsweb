<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsVerifikatorToPermohonan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->string('petugas_verifikator')->nullable();
            $table->bigInteger('petugas_verifikator_id')->nullable();
            $table->bigInteger('petugas_bt')->nullable();
            $table->bigInteger('petugas_bt_id')->nullable();
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
            $table->dropColumn('petugas_verifikator');
            $table->dropColumn('petugas_verifikator_id');
            $table->dropColumn('petugas_bt');
            $table->dropColumn('petugas_bt_id');
        });
    }
}
