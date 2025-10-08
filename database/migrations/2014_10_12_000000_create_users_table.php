<?php

// 1. Migration: create_x01_dm_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('x01_dm_users', function (Blueprint $table) {
            $table->id();
            $table->string('id_kode', 20)->unique()->comment('X010725001');
            $table->string('nik_kry', 20);
            $table->string('nama_kry', 100);
            $table->string('departemen_kry', 50);
            $table->string('jabatan_kry', 50);
            $table->string('wilker_kry', 50);
            $table->string('password_kry');
            $table->string('created_by', 50);
            $table->string('updated_by', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('x01_dm_users');
    }
};