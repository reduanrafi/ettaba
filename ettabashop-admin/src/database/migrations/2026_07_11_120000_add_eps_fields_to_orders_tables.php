<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddEpsFieldsToOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add fields to orders table
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'transaction_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('transaction_id')->nullable()->after('unique_order_id')->index();
                $table->string('payment_status')->default('pending')->after('transaction_id');
                $table->decimal('virtual_balance_used', 10, 2)->default(0.00)->after('payment_status');
            });
        }

        // 2. Add fields to anonymous_orders table
        if (Schema::hasTable('anonymous_orders') && !Schema::hasColumn('anonymous_orders', 'transaction_id')) {
            Schema::table('anonymous_orders', function (Blueprint $table) {
                $table->string('transaction_id')->nullable()->after('unique_order_id')->index();
                $table->string('payment_status')->default('pending')->after('transaction_id');
                $table->decimal('virtual_balance_used', 10, 2)->default(0.00)->after('payment_status');
            });
        }

        // 3. Modify status enums on both tables to allow 'pending_payment'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed', 'pending_payment') DEFAULT 'pending'");
        DB::statement("ALTER TABLE anonymous_orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed', 'pending_payment') DEFAULT 'pending'");

        // 4. Seed the EPS Payment Method
        DB::table('payment_methods')->insertOrIgnore([
            [
                'id' => 2,
                'name' => 'Easy Payment System (EPS)',
                'short_code' => 'EPS',
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
        // Remove EPS Payment Method
        DB::table('payment_methods')->where('id', 2)->delete();

        // Revert status enums (excluding 'pending_payment' might cause issues if data exists, so proceed with caution)
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed') DEFAULT 'pending'");
        DB::statement("ALTER TABLE anonymous_orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed') DEFAULT 'pending'");

        // Remove fields from anonymous_orders table
        Schema::table('anonymous_orders', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payment_status', 'virtual_balance_used']);
        });

        // Remove fields from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'payment_status', 'virtual_balance_used']);
        });
    }
}
