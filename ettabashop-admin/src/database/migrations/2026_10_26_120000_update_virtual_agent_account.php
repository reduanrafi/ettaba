<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update user 291 or accounts named 'Virtual Agent' or phone '010000' to virtual_agent customer_type
        // with partner_limit = 0, merchant_limit = 0, and customer_limit = 999999
        DB::table('users')
            ->where('id', 291)
            ->orWhere('phone', '010000')
            ->orWhere('name', 'Virtual Agent')
            ->update([
                'customer_type' => 'virtual_agent',
                'partner_limit' => 0,
                'merchant_limit' => 0,
                'customer_limit' => 999999
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('id', 291)
            ->orWhere('phone', '010000')
            ->orWhere('name', 'Virtual Agent')
            ->update([
                'customer_type' => 'buy_only',
                'customer_limit' => 0
            ]);
    }
};
