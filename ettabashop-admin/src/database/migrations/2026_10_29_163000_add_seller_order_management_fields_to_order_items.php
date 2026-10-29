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
                    $table->unsignedBigInteger('owner_id')->nullable();
                }
                if (!Schema::hasColumn('order_items', 'trp')) {
                    $table->double('trp')->default(0);
                }
                if (!Schema::hasColumn('order_items', 'seller_status')) {
                    $table->string('seller_status', 30)->default('new');
                }
                if (!Schema::hasColumn('order_items', 'seller_cancel_reason')) {
                    $table->text('seller_cancel_reason')->nullable();
                }
                if (!Schema::hasColumn('order_items', 'seller_seen')) {
                    $table->boolean('seller_seen')->default(0);
                }
                if (!Schema::hasColumn('order_items', 'seller_updated_at')) {
                    $table->timestamp('seller_updated_at')->nullable();
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'delivery_charge_id')) {
                    $table->unsignedBigInteger('delivery_charge_id')->nullable();
                }
                if (!Schema::hasColumn('orders', 'delivery_charge')) {
                    $table->double('delivery_charge')->default(0);
                }
            });
        }

        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('product_reviews', 'product_id')) {
                    $table->unsignedBigInteger('product_id')->nullable();
                }
                if (!Schema::hasColumn('product_reviews', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                }
                if (!Schema::hasColumn('product_reviews', 'rating')) {
                    $table->double('rating')->default(5);
                }
                if (!Schema::hasColumn('product_reviews', 'review_message')) {
                    $table->text('review_message')->nullable();
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

