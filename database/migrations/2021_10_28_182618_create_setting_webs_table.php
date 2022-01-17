<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingWebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_webs', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('color')->nullable();
            $table->string('app_name')->nullable();
            $table->string('footer_text')->default('© 2021 YourECommerce')->nullable();
            $table->string('ipaymu_api')->nullable();
            $table->string('ipaymu_va')->nullable();
            $table->string('ipaymu_url')->nullable();
            $table->integer('ipaymu_production')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_webs');
    }
}
