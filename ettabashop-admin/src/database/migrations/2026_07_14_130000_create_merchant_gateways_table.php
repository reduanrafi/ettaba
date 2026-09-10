<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMerchantGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('charge_percent', 5, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Seed initial values
        DB::table('merchant_gateways')->insert([
            [
                'name' => 'MFS (bKash, Nagad, Rocket, Upay etc)',
                'code' => 'mfs',
                'charge_percent' => 2.00,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Visa / MasterCard',
                'code' => 'card',
                'charge_percent' => 2.50,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'American Express',
                'code' => 'amex',
                'charge_percent' => 3.50,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
        Schema::dropIfExists('merchant_gateways');
    }
}
