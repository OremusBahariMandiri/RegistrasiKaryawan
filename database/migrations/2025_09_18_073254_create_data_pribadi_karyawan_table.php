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
        Schema::create('x03_dt_pribadi_calon_kry', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode', 20)->unique()->comment('X030725001');
            $table->string('no_calon_kry', 20);
            $table->date('tgl_daftar');
            $table->string('nik_ktp_c_kry', 20);
            $table->string('nama_c_kry', 100);
            $table->string('tempat_lhr_c_kry', 50);
            $table->date('tanggal_lhr_c_kry');
            $table->enum('sex_c_kry', ['L', 'P']);
            $table->string('agama_c_kry', 20);
            $table->enum('sts_kawin_c_kry', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']);
            $table->string('pekerjaan_c_kry', 50)->nullable();
            $table->enum('warganegara_c_kry', ['WNI', 'WNA']);
            $table->string('telpon1_c_kry', 20);
            $table->string('telpon2_c_kry', 20)->nullable();
            $table->string('email_c_kry', 100);
            $table->string('instagram_c_kry', 50)->nullable();
            $table->text('alamat_ktp_c_kry');
            $table->string('rt_rw_ktp_c_kry', 10);
            $table->string('kelurahan_ktp_c_kry', 50);
            $table->string('kecamatan_ktp_c_kry', 50);
            $table->string('kota_ktp_c_kry', 50);
            $table->string('provinsi_ktp_c_kry', 50);
            $table->string('kode_pos_ktp_c_kry', 10);
            $table->text('alamat_dom_c_kry');
            $table->string('rt_rw_dom_c_kry', 10);
            $table->string('kelurahan_dom_c_kry', 50);
            $table->string('kecamatan_dom_c_kry', 50);
            $table->string('kota_dom_c_kry', 50);
            $table->string('provinsi_dom_c_kry', 50);
            $table->string('kode_pos_dom_c_kry', 10);
            $table->text('domisili_c_kry');
            $table->text('hobi_ckry_c_kry')->nullable();
            $table->text('kelebihan_c_kry')->nullable();
            $table->text('kekurangan_c_kry')->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_kode')->on('x01_dm_users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id_kode')->on('x01_dm_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('x03_dt_pribadi_calon_kry');
    }
};
