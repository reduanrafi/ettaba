<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandCashOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hand_cash_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hand_cash_order_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('hand_cash_product_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('trp');
            $table->integer('tcb');

            $table->timestamps();
            $table->foreign('hand_cash_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('hand_cash_product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hand_cash_order_items');
    }
}
