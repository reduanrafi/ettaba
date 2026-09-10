<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersAndProductsForReferralSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('partner_limit')->default(0)->after('referral_limit');
            $table->integer('customer_limit')->default(0)->after('partner_limit');
        });

        Schema::table('hand_cash_products', function (Blueprint $table) {
            $table->decimal('direct_refer_commission', 10, 2)->default(0)->after('cb_en');
        });

        Schema::table('pending_points', function (Blueprint $table) {
            $table->enum('point_type', ['own', 'team', 'referral'])->default('own')->after('amount');
        });

        Schema::table('user_pending_funds', function (Blueprint $table) {
            $table->enum('point_type', ['own', 'team', 'referral'])->default('own')->after('amount');
            $table->unsignedBigInteger('child_id')->nullable()->after('point_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['partner_limit', 'customer_limit']);
        });

        Schema::table('hand_cash_products', function (Blueprint $table) {
            $table->dropColumn('direct_refer_commission');
        });

        Schema::table('pending_points', function (Blueprint $table) {
            $table->dropColumn('point_type');
        });

        Schema::table('user_pending_funds', function (Blueprint $table) {
            $table->dropColumn(['point_type', 'child_id']);
        });
    }
}

