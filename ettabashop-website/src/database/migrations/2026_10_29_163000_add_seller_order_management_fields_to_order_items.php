<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerOrderManagementFieldsToOrderItems extends Migration
{
    public function up()
    {
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'owner_id')) {
                    $table->unsignedBigInteger('owner_id')->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('order_items', 'trp')) {
                    $table->double('trp')->default(0)->after('price');
                }
                if (!Schema::hasColumn('order_items', 'seller_status')) {
                    $table->string('seller_status', 30)->default('new')->after('product_unit');
                }
                if (!Schema::hasColumn('order_items', 'seller_cancel_reason')) {
                    $table->text('seller_cancel_reason')->nullable()->after('seller_status');
                }
                if (!Schema::hasColumn('order_items', 'seller_seen')) {
                    $table->boolean('seller_seen')->default(0)->after('seller_cancel_reason');
                }
                if (!Schema::hasColumn('order_items', 'seller_updated_at')) {
                    $table->timestamp('seller_updated_at')->nullable()->after('seller_seen');
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'delivery_charge')) {
                    $table->double('delivery_charge')->default(0)->after('delivery_charge_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $cols = ['seller_status', 'seller_cancel_reason', 'seller_seen', 'seller_updated_at'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('order_items', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
}

