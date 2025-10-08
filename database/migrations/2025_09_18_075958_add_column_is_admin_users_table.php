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
        Schema::table('x01_dm_users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password_kry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('x01dmuser', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
