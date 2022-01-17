<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRajaongkirApiToSettingWebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_webs', function (Blueprint $table) {
            $table->string('rajaongkir_api_key')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_webs', function (Blueprint $table) {
            $table->dropColumn('rajaongkir_api_key');
        });
    }
}
