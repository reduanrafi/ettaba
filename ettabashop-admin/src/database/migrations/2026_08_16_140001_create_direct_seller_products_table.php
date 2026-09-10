<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectSellerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_seller_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name_bn')->nullable();
            $table->string('name_en')->nullable();
            $table->decimal('company_rate', 10, 2)->default(0.00);
            $table->decimal('seller_rate', 10, 2)->default(0.00);
            $table->decimal('erp', 10, 2)->default(0.00);
            $table->decimal('refer_commission', 10, 2)->default(0.00);
            $table->integer('qty')->default(0);
            $table->decimal('tcb', 10, 2)->default(0.00);
            $table->decimal('reward_points', 10, 2)->default(0.00);
            $table->decimal('vat', 10, 2)->default(0.00);
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
        Schema::dropIfExists('direct_seller_products');
    }
}
