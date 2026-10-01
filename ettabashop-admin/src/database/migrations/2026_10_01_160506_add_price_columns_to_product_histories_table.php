<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnsToProductHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_histories', function (Blueprint $table) {
            $table->string('old_rate')->nullable();
            $table->string('old_mrp')->nullable();
            $table->string('old_erp')->nullable();
            $table->string('old_cb')->nullable();
            $table->string('old_direct_refer_commission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_histories', function (Blueprint $table) {
            $table->dropColumn(['old_rate', 'old_mrp', 'old_erp', 'old_cb', 'old_direct_refer_commission']);
        });
    }
}
