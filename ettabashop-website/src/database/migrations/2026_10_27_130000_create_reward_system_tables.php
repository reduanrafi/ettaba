<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Rewards Table
        if (!Schema::hasTable('rewards')) {
            Schema::create('rewards', function (Blueprint $table) {
                $table->id();
                $table->enum('account_type', ['customer', 'partner', 'merchant'])->default('customer');
                $table->integer('level_no')->default(1);
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('reward_name');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->double('required_points')->default(0);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_visible')->default(1);
                $table->string('info_title')->nullable()->default('পুরস্কার সম্পর্কিত তথ্য:');
                $table->text('info_items')->nullable();
                $table->string('howto_title')->nullable()->default('কীভাবে Point অর্জন করবেন?');
                $table->text('howto_items')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index(['account_type', 'is_visible', 'sort_order']);
            });
        }

        // 2. Reward Applications Table
        if (!Schema::hasTable('reward_applications')) {
            Schema::create('reward_applications', function (Blueprint $table) {
                $table->id();
                $table->string('application_no')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('reward_id');
                $table->string('account_type')->default('customer');
                
                // Snapshots at time of application
                $table->string('reward_title');
                $table->string('reward_name');
                $table->double('required_points')->default(0);
                $table->string('reward_image')->nullable();

                // User details snapshot
                $table->string('user_name')->nullable();
                $table->string('user_phone')->nullable();
                $table->string('user_nid')->nullable();

                // Status & Admin decision
                $table->enum('status', ['pending', 'accepted', 'received', 'rejected'])->default('pending');
                $table->text('admin_note')->nullable();
                $table->string('collection_date')->nullable();
                $table->string('collection_time')->nullable();
                $table->string('collection_place')->nullable();

                // Point deduction tracking
                $table->boolean('points_deducted')->default(0);
                $table->double('points_deducted_amount')->default(0);
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();

                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['reward_id']);
            });
        }

        // 3. Online Categories Table (Dynamic configuration, UI representation)
        if (!Schema::hasTable('online_categories')) {
            Schema::create('online_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_visible')->default(1);
                $table->timestamps();

                $table->index(['is_visible', 'sort_order']);
            });
        }

        // 4. Reward Settings Table
        if (!Schema::hasTable('reward_settings')) {
            Schema::create('reward_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // 5. User Notifications Table
        if (!Schema::hasTable('user_notifications')) {
            Schema::create('user_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('reward');
                $table->string('link')->nullable();
                $table->boolean('is_read')->default(0);
                $table->timestamps();

                $table->index(['user_id', 'is_read']);
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
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('reward_settings');
        Schema::dropIfExists('online_categories');
        Schema::dropIfExists('reward_applications');
        Schema::dropIfExists('rewards');
    }
}
