<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('x09_dt_riwayat_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_x03', 20);
            $table->string('id_kode', 20)->unique()->comment('X090725001');
            $table->string('perusahaan_rkj', 100);
            $table->string('departemen_rkj', 50);
            $table->string('jabatan_rkj', 50);
            $table->string('wilker_rkj', 50);
            $table->date('tgl_mulai_rkj');
            $table->date('tgl_berakhir_rkj');
            $table->string('masa_kerja_rkj', 20);
            $table->decimal('penghasilan_rkj', 15, 2)->nullable();
            $table->text('ket_berhenti_rkj')->nullable();
            $table->string('nama_ref', 100);
            $table->enum('sex_ref', ['L', 'P']);
            $table->string('departemen_ref', 50);
            $table->string('jabatan_ref', 50);
            $table->string('telpon_ref', 20);
            $table->string('hubungan_ref', 50);
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_kode')->on('x01_dm_users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id_kode')->on('x01_dm_users')->onDelete('cascade');
            $table->foreign('id_kode_x03')->references('id_kode')->on('x03_dt_pribadi_calon_kry')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('x09_dt_riwayat_kerja');
    }
};
