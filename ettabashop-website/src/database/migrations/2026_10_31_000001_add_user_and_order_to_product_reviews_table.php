<?php

namespace App\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('product_reviews', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('product_id');
                }
                if (!Schema::hasColumn('product_reviews', 'order_id')) {
                    $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                if (Schema::hasColumn('product_reviews', 'order_id')) {
                    $table->dropColumn('order_id');
                }
                if (Schema::hasColumn('product_reviews', 'user_id')) {
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};

