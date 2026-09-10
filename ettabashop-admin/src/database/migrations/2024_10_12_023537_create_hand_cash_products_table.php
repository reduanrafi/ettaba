<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandCashProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hand_cash_products')) {
            Schema::create('hand_cash_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('unique_id')->nullable();

            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_bn')->nullable();

            $table->text('delivery_area_en')->nullable();
            $table->text('delivery_area_bn')->nullable();

            $table->string('featured_image')->nullable();

            $table->string('rate_en')->nullable();
            $table->string('rate_bn')->nullable();

            $table->string('mrp_en')->nullable();
            $table->string('mrp_bn')->nullable();

            $table->string('erp_en')->nullable();
            $table->string('erp_bn')->nullable();

            $table->string('cb_en')->nullable();
            $table->string('cb_bn')->nullable();

            $table->string('tcb_en')->nullable();
            $table->string('tcb_bn')->nullable();

            $table->string('trp_en')->nullable();
            $table->string('trp_bn')->nullable();

            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            //text_percent changed to sold_amount.
            $table->integer( 'sold_amount')->nullable();
            $table->integer('vat_percent')->nullable();
            $table->integer('discount')->nullable();
            $table->boolean('is_sold_out')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('hand_cash_products');
    }
}
