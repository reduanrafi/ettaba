<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShopsAndProductsForRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Update shops table
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (!Schema::hasColumn('shops', 'nid_tin')) {
                    $table->string('nid_tin')->nullable()->after('mobile');
                }
                if (!Schema::hasColumn('shops', 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable()->after('nid_tin');
                }
                if (!Schema::hasColumn('shops', 'division_id')) {
                    $table->unsignedBigInteger('division_id')->nullable()->after('address_bn');
                }
                if (!Schema::hasColumn('shops', 'district_id')) {
                    $table->unsignedBigInteger('district_id')->nullable()->after('division_id');
                }
                if (!Schema::hasColumn('shops', 'upazila_id')) {
                    $table->unsignedBigInteger('upazila_id')->nullable()->after('district_id');
                }
                if (!Schema::hasColumn('shops', 'union_name')) {
                    $table->string('union_name')->nullable()->after('upazila_id');
                }
                if (!Schema::hasColumn('shops', 'ward_no')) {
                    $table->string('ward_no')->nullable()->after('union_name');
                }
                if (!Schema::hasColumn('shops', 'instagram_link')) {
                    $table->string('instagram_link')->nullable()->after('youtube_link');
                }
                if (!Schema::hasColumn('shops', 'social_approved')) {
                    $table->boolean('social_approved')->default(0)->after('instagram_link');
                }
                if (!Schema::hasColumn('shops', 'delivery_type')) {
                    $table->enum('delivery_type', ['ettaba', 'self'])->default('ettaba')->after('social_approved');
                }
                if (!Schema::hasColumn('shops', 'erp_margin_percent')) {
                    $table->decimal('erp_margin_percent', 5, 2)->default(5.00)->after('delivery_type');
                }
                if (!Schema::hasColumn('shops', 'seller_ebalance')) {
                    $table->decimal('seller_ebalance', 14, 2)->default(0.00)->after('erp_margin_percent');
                }
                if (!Schema::hasColumn('shops', 'allowed_categories')) {
                    $table->text('allowed_categories')->nullable()->after('seller_ebalance');
                }
            });
        }

        // 2. Update blocked_users table
        if (Schema::hasTable('blocked_users')) {
            Schema::table('blocked_users', function (Blueprint $table) {
                if (!Schema::hasColumn('blocked_users', 'type')) {
                    $table->string('type')->default('all')->nullable()->after('phone');
                }
            });
        }

        // 3. Update products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'sizes')) {
                    $table->text('sizes')->nullable()->after('weight_unit');
                }
                if (!Schema::hasColumn('products', 'colors')) {
                    $table->text('colors')->nullable()->after('sizes');
                }
            });
        }

        // 4. Update order_items table
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'selected_size')) {
                    $table->string('selected_size')->nullable()->after('product_unit');
                }
                if (!Schema::hasColumn('order_items', 'selected_color')) {
                    $table->string('selected_color')->nullable()->after('selected_size');
                }
                if (!Schema::hasColumn('order_items', 'delivery_type')) {
                    $table->string('delivery_type')->default('ettaba')->after('selected_color');
                }
                if (!Schema::hasColumn('order_items', 'seller_due_deducted')) {
                    $table->boolean('seller_due_deducted')->default(0)->after('delivery_type');
                }
                if (!Schema::hasColumn('order_items', 'seller_due_amount')) {
                    $table->decimal('seller_due_amount', 12, 2)->default(0.00)->after('seller_due_deducted');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}

