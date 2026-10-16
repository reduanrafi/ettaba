<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add virtual_agent to customer_type enum in users table
        DB::statement("ALTER TABLE users MODIFY COLUMN customer_type ENUM('buy_only', 'buy_earn', 'virtual_agent') NULL DEFAULT 'buy_only'");

        // 2. Add type and ward_count to upazilas table if not exists
        if (Schema::hasTable('upazilas')) {
            Schema::table('upazilas', function (Blueprint $table) {
                if (!Schema::hasColumn('upazilas', 'type')) {
                    $table->string('type', 30)->default('upazila')->after('bn_name');
                }
                if (!Schema::hasColumn('upazilas', 'ward_count')) {
                    $table->integer('ward_count')->default(0)->after('type');
                }
            });
        }

        // 3. Ensure unions table exists and has proper columns
        if (!Schema::hasTable('unions')) {
            Schema::create('unions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('upazila_id');
                $table->string('name', 100)->nullable();
                $table->string('bn_name', 100);
                $table->string('type', 30)->default('union');
                $table->integer('ward_count')->default(9);
                $table->string('url', 150)->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('unions', function (Blueprint $table) {
                if (!Schema::hasColumn('unions', 'ward_count')) {
                    $table->integer('ward_count')->default(9)->after('type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive rollback needed
    }
};
