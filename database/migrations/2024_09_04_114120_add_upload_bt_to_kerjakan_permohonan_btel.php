<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadBtToKerjakanPermohonanBtel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kerjakan_permohonan_btel', function (Blueprint $table) {
            $table->string('upload_bt',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kerjakan_permohonan_btel', function (Blueprint $table) {
            $table->dropColumn('upload_bt');
        });
    }
}
