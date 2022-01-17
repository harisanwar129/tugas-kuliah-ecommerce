<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method')->default('IPAYMU');
            $table->string('status')->default('PENDING');
            $table->bigInteger('subtotal')->default(0);
            $table->integer('status_code')->default(0);
            $table->text('sid')->nullable();
            $table->text('payment_url')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->text('download_link')->nullable();
            $table->integer('is_read')->default(0);
            $table->foreignId('user_id');
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
        Schema::dropIfExists('transactions');
    }
}
