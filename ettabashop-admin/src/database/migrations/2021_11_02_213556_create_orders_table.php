<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('unique_order_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('trp')->nullable();
            $table->string('tcb')->nullable();
            $table->string('net_total')->nullable();
            $table->string('erp_total')->nullable();
            $table->string('rate_total')->nullable();

            $table->enum('status',['pending','accepted','canceled','on_delivery','delivered','completed'])->default('pending');
            $table->timestamps();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function($table)
        {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['owner_id','user_id','payment_method_id']);
        });
        Schema::dropIfExists('orders');
    }
}
