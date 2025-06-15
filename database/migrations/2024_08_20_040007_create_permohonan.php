<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id('id_permohonan');
            $table->string('no_permohonan', 50);
            $table->date('tanggal_input')->nullable();
            $table->string('nama_pemohon', 255);
            $table->bigInteger('telepon_pemohon');
            $table->string('nama_kuasa', 255)->nullable();
            $table->bigInteger('telepon_kuasa')->nullable();
            $table->string('jenis_hak', 255)->nullable();
            $table->string('no_sertifikat', 50);
            $table->integer('provinsi_id');
            $table->integer('kabupaten_id');
            $table->integer('kecamatan_id');
            $table->string('desa_id', 20);
            $table->string('file_sertifikat', 255)->nullable();
            $table->string('file_foto', 255)->nullable();
            $table->string('keperluan', 255)->nullable();
            $table->string('petugas', 255)->comment('{petugas-elektronik}')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('out', 100)->nullable();
            $table->string('jenis_pemetaan', 255)->nullable();
            $table->string('petugas_pemetaan')->nullable();
            $table->string('status_pemetaan', 255)->nullable();
            $table->string('petugas_pengukuran', 100)->nullable();
            $table->string('status_pengukuran', 255)->nullable();
            $table->string('petugas_su_el', 100)->nullable();
            $table->string('status_su_el', 100)->nullable();
            $table->string('catatan_su_el', 100)->nullable();
            $table->enum('upload_bt', ['sudah','proses'])->nullable();
            $table->string('petugas_bt_el', 100)->nullable();
            $table->string('status_bt_el', 100)->nullable();
            $table->string('catatan_bt_el', 100)->nullable();
            $table->date('tanggal_setor')->nullable();
            $table->string('status_permohonan', 50)->nullable();
            $table->string('pemberitahuan', 255)->nullable();
            $table->string('latitude', 255)->nullable();
            $table->string('longitude', 255)->nullable();
            $table->string('luas_total', 255)->nullable();
            $table->string('jarak_total', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permohonan');
    }
}
