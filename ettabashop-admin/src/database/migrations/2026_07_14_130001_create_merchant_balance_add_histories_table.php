<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantBalanceAddHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_balance_add_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('gateway_name');
            $table->decimal('gateway_charge', 15, 2);
            $table->decimal('total_paid', 15, 2);
            $table->string('status')->default('pending'); // pending, success, failed, cancelled, refunded
            $table->string('payment_reference')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_balance_add_histories');
    }
}
