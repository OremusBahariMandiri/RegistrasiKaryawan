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
        Schema::create('x05_dt_keluarga_inti', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_x03', 20);
            $table->string('id_kode', 20)->unique()->comment('X050725001');
            $table->string('sts_ki', 50); // Suami, Istri, Anak Ke 1, dll
            $table->string('nama_ki', 100);
            $table->enum('sex_ki', ['L', 'P']);
            $table->date('tgl_lahir_ki');
            $table->string('ijazah_ki', 50);
            $table->string('institusi_ki', 100);
            $table->string('jurusan_ki', 100)->nullable();
            $table->string('pekerjaan_ki', 50);
            $table->text('domisili_ki');
            $table->string('no_telp_ki', 20)->nullable();
            $table->enum('keberadaan_ki', ['Hidup', 'Meninggal'])->default('Hidup');
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
        Schema::dropIfExists('x05_dt_keluarga_inti');
    }
};
