<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnonymousOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('unique_order_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('trp')->nullable();
            $table->string('tcb')->nullable();
            $table->string('net_total')->nullable();
            $table->string('erp_total')->nullable();
            $table->string('rate_total')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->enum('status',['pending','accepted','canceled','on_delivery','delivered','completed'])->default('pending');

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
        Schema::dropIfExists('anonymous_orders');
    }
}
