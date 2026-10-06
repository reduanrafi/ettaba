<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProductSnapshotToOrderItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. order_items
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'product_name')) {
                    $table->string('product_name')->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('order_items', 'product_unit')) {
                    $table->string('product_unit')->nullable()->after('product_name');
                }
            });

            // Backfill existing order items
            try {
                DB::statement("UPDATE order_items oi INNER JOIN products p ON oi.product_id = p.id SET oi.product_name = p.name_en, oi.product_unit = p.unit WHERE oi.product_name IS NULL");
            } catch (\Exception $e) {
                // ignore if table empty or query fail
            }
        }

        // 2. hand_cash_order_items
        if (Schema::hasTable('hand_cash_order_items')) {
            Schema::table('hand_cash_order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('hand_cash_order_items', 'product_name')) {
                    $table->string('product_name')->nullable()->after('hand_cash_product_id');
                }
                if (!Schema::hasColumn('hand_cash_order_items', 'product_unit')) {
                    $table->string('product_unit')->nullable()->after('product_name');
                }
            });

            // Backfill existing hand cash order items
            try {
                DB::statement("UPDATE hand_cash_order_items hcoi INNER JOIN hand_cash_products hcp ON hcoi.hand_cash_product_id = hcp.id SET hcoi.product_name = hcp.name_en, hcoi.product_unit = hcp.unit WHERE hcoi.product_name IS NULL");
            } catch (\Exception $e) {
                // ignore
            }
        }

        // 3. anonymous_order_items
        if (Schema::hasTable('anonymous_order_items')) {
            Schema::table('anonymous_order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('anonymous_order_items', 'product_name')) {
                    $table->string('product_name')->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('anonymous_order_items', 'product_unit')) {
                    $table->string('product_unit')->nullable()->after('product_name');
                }
            });

            // Backfill existing anonymous order items
            try {
                DB::statement("UPDATE anonymous_order_items aoi INNER JOIN products p ON aoi.product_id = p.id SET aoi.product_name = p.name_en, aoi.product_unit = p.unit WHERE aoi.product_name IS NULL");
            } catch (\Exception $e) {
                // ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn(['product_name', 'product_unit']);
            });
        }

        if (Schema::hasTable('hand_cash_order_items')) {
            Schema::table('hand_cash_order_items', function (Blueprint $table) {
                $table->dropColumn(['product_name', 'product_unit']);
            });
        }

        if (Schema::hasTable('anonymous_order_items')) {
            Schema::table('anonymous_order_items', function (Blueprint $table) {
                $table->dropColumn(['product_name', 'product_unit']);
            });
        }
    }
}
