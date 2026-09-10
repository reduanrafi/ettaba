<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('direct_sales_orders')) {
            Schema::create('direct_sales_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('customer_name')->nullable();
                $table->string('customer_phone');
                $table->text('customer_address')->nullable();
                $table->unsignedBigInteger('product_id');
                $table->integer('qty')->default(1);
                $table->decimal('company_rate', 10, 2)->default(0.00);
                $table->decimal('seller_rate', 10, 2)->default(0.00);
                $table->decimal('erp', 10, 2)->default(0.00);
                $table->decimal('refer_commission', 10, 2)->default(0.00);
                $table->decimal('tcb', 10, 2)->default(0.00);
                $table->decimal('reward_points', 10, 2)->default(0.00);
                $table->decimal('vat', 10, 2)->default(0.00);
                $table->decimal('total_company_rate', 10, 2)->default(0.00);
                $table->decimal('total_seller_rate', 10, 2)->default(0.00);
                $table->decimal('total_erp', 10, 2)->default(0.00);
                $table->decimal('total_refer_commission', 10, 2)->default(0.00);
                $table->decimal('total_tcb', 10, 2)->default(0.00);
                $table->decimal('total_reward_points', 10, 2)->default(0.00);
                $table->decimal('total_vat', 10, 2)->default(0.00);
                $table->enum('status', ['completed', 'cancelled'])->default('completed');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('direct_seller_products')->onDelete('cascade');
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
        Schema::dropIfExists('direct_sales_orders');
    }
}
