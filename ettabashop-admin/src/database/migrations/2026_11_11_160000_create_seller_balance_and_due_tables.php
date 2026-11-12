<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerBalanceAndDueTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('seller_balance_add_histories')) {
            Schema::create('seller_balance_add_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('shop_id')->nullable();
                $table->string('transaction_id')->unique();
                $table->decimal('amount', 14, 2);
                $table->string('gateway_name')->nullable();
                $table->decimal('gateway_charge', 14, 2)->default(0.00);
                $table->decimal('total_paid', 14, 2)->default(0.00);
                $table->string('payment_reference')->nullable();
                $table->string('status')->default('pending'); // pending, success, failed, cancelled
                $table->timestamps();

                $table->index('user_id');
                $table->index('shop_id');
                $table->index('transaction_id');
                $table->index('status');
            });
        }

        if (!Schema::hasTable('seller_due_deduction_histories')) {
            Schema::create('seller_due_deduction_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('shop_id');
                $table->unsignedBigInteger('order_id');
                $table->decimal('amount', 14, 2);
                $table->decimal('balance_before', 14, 2)->default(0.00);
                $table->decimal('balance_after', 14, 2)->default(0.00);
                $table->string('note')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('shop_id');
                $table->index('order_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_due_deduction_histories');
        Schema::dropIfExists('seller_balance_add_histories');
    }
}

