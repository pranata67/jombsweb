<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerjakanPermohonanPemetaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kerjakan_permohonan_pemetaan', function (Blueprint $table) {
            $table->id('id_kerjakan_permohonan_pemetaan');
            $table->bigInteger('permohonan_id');
            $table->bigInteger('user_id');
            $table->string('status_pemetaan')->nullable();
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
        Schema::dropIfExists('kerjakan_permohonan_pemetaan');
    }
}
