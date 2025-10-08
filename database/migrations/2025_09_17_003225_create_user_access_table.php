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
        Schema::create('x02_dm_user_access', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode_x01', 20);
            $table->string('menu_acs', 50);
            $table->boolean('tambah_acs')->default(false);
            $table->boolean('ubah_acs')->default(false);
            $table->boolean('hapus_acs')->default(false);
            $table->boolean('download_acs')->default(false);
            $table->boolean('detail_acs')->default(false);
            $table->boolean('monitoring_acs')->default(false);
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_kode_x01')->references('id_kode')->on('x01_dm_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('x02_dm_user_access');
    }
};
