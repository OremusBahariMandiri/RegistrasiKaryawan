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
        Schema::create('x06_dt_pendidikan_formal', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_x03', 20);
            $table->string('id_kode', 20)->unique()->comment('X060725001');
            $table->string('ijazah_c_kry', 50); // SD, SMP, SMA, D1, D2, D3, S1, S2, S3
            $table->string('institusi_c_kry', 100);
            $table->string('jurusan_c_kry', 100)->nullable();
            $table->string('kota_c_kry', 50);
            $table->date('tgl_lulus_c_kry');
            $table->string('gelar_c_kry', 50)->nullable();
            $table->enum('sts_surat_lulus_ckry', ['Ada', 'Tidak Ada'])->default('Ada');
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
        Schema::dropIfExists('x06_dt_pendidikan_formal');
    }
};
