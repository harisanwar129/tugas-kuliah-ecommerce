<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingColumnsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('shipping_courier_code')->nullable();
            $table->string('shipping_service_name')->nullable();
            $table->bigInteger('shipping_service_price')->nullable()->default(0);
            $table->string('shipping_city_from')->nullable();
            $table->string('shipping_city_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('shipping_courier_code');
            $table->dropColumn('shipping_service_name');
            $table->dropColumn('shipping_service_price');
            $table->dropColumn('shipping_city_from');
            $table->dropColumn('shipping_city_to');
        });
    }
}
