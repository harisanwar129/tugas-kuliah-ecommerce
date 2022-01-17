<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('thumbnail')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->string('price_striked')->nullable();
            $table->text('tags')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('product_category_id')->nullable();
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
        Schema::dropIfExists('products');
    }
}
