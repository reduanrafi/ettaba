<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectReferCommissionToProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('direct_refer_commission')->nullable()->after('unit');
        });
        Schema::table('hand_cash_products', function (Blueprint $table) {
            $table->string('direct_refer_commission')->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('direct_refer_commission');
        });
        Schema::table('hand_cash_products', function (Blueprint $table) {
            $table->dropColumn('direct_refer_commission');
        });
    }
}
