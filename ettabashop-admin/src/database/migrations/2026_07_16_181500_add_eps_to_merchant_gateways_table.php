<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddEpsToMerchantGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('merchant_gateways')->insertOrIgnore([
            [
                'name' => 'Easy Payment System (EPS)',
                'code' => 'eps',
                'charge_percent' => 1.50,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('merchant_gateways')->where('code', 'eps')->delete();
    }
}
