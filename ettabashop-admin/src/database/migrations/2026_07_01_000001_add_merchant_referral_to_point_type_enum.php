<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMerchantReferralToPointTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE pending_points MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant', 'merchant_referral') DEFAULT 'own'");
        DB::statement("ALTER TABLE user_pending_funds MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant', 'merchant_referral') DEFAULT 'own'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE pending_points MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant') DEFAULT 'own'");
        DB::statement("ALTER TABLE user_pending_funds MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant') DEFAULT 'own'");
    }
}
