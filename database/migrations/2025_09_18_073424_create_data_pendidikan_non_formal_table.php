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
        Schema::create('x07_dt_pendidikan_non_formal', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_x03', 20);
            $table->string('id_kode', 20)->unique()->comment('X070725001');
            $table->enum('jenis_kegiatan', ['Kursus', 'Seminar', 'Pelatihan']);
            $table->string('nama_kegiatan', 100);
            $table->string('penyelenggara', 100);
            $table->string('lokasi_kegiatan', 100);
            $table->date('tgl_mulai');
            $table->date('tgl_berakhir');
            $table->string('waktu_pelaksanaan', 50);
            $table->enum('sts_sertifikasi', ['Ada', 'Tidak Ada'])->default('Tidak Ada');
            $table->string('file_documen', 255)->nullable();
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
        Schema::dropIfExists('x07_dt_pendidikan_non_formal');
    }
};
