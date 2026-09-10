<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePointTypeEnums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding 'merchant' and ensuring 'team' is present.
        // In MySQL, we need to use a raw query to update enums easily without losing data
        DB::statement("ALTER TABLE pending_points MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant') DEFAULT 'own'");
        DB::statement("ALTER TABLE user_pending_funds MODIFY COLUMN point_type ENUM('own', 'team', 'referral', 'merchant') DEFAULT 'own'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE pending_points MODIFY COLUMN point_type ENUM('own', 'team', 'referral') DEFAULT 'own'");
        DB::statement("ALTER TABLE user_pending_funds MODIFY COLUMN point_type ENUM('own', 'team', 'referral') DEFAULT 'own'");
    }
}
