<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateHomepageAndAdminControlsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Enhance sliders table
        if (Schema::hasTable('sliders')) {
            Schema::table('sliders', function (Blueprint $table) {
                if (!Schema::hasColumn('sliders', 'button_text')) {
                    $table->string('button_text')->nullable()->default('এখনই কেনাকাটা করুন');
                }
                if (!Schema::hasColumn('sliders', 'button_link')) {
                    $table->string('button_link')->nullable();
                }
                if (!Schema::hasColumn('sliders', 'offer_details')) {
                    $table->longText('offer_details')->nullable();
                }
                if (!Schema::hasColumn('sliders', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
                if (!Schema::hasColumn('sliders', 'is_active')) {
                    $table->boolean('is_active')->default(1);
                }
            });
        }

        // 2. Enhance categories table
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'priority')) {
                    $table->integer('priority')->default(0);
                }
                if (!Schema::hasColumn('categories', 'icon_class')) {
                    $table->string('icon_class')->nullable();
                }
                if (!Schema::hasColumn('categories', 'icon_color')) {
                    $table->string('icon_color')->nullable()->default('#16a34a');
                }
                if (!Schema::hasColumn('categories', 'show_on_home')) {
                    $table->boolean('show_on_home')->default(1);
                }
                if (!Schema::hasColumn('categories', 'is_active')) {
                    $table->boolean('is_active')->default(1);
                }
            });
        }

        // 3. Create home_sections table
        if (!Schema::hasTable('home_sections')) {
            Schema::create('home_sections', function (Blueprint $table) {
                $table->id();
                $table->string('title_bn');
                $table->string('title_en')->nullable();
                $table->string('slug')->unique();
                $table->string('icon')->nullable()->default('fas fa-bolt');
                $table->string('icon_color')->nullable()->default('#f59e0b');
                $table->string('section_type')->default('category'); // category, auto_cashback, auto_point, featured, all
                $table->text('category_ids')->nullable(); // JSON array of category IDs
                $table->integer('product_limit')->default(4);
                $table->integer('sort_order')->default(0);
                $table->boolean('is_visible')->default(1);
                $table->timestamps();

                $table->index(['is_visible', 'sort_order']);
            });

            // Seed default 8 sections in exact order
            $now = now();
            $defaultSections = [
                [
                    'title_bn' => 'লোকাল পণ্য',
                    'title_en' => 'Local Products',
                    'slug' => 'local-products',
                    'icon' => 'fas fa-shopping-basket',
                    'icon_color' => '#16a34a',
                    'section_type' => 'category',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 1,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'অধিক ক্যাশব্যাকের পণ্য',
                    'title_en' => 'High Cashback Products',
                    'slug' => 'high-cashback',
                    'icon' => 'fas fa-percentage',
                    'icon_color' => '#16a34a',
                    'section_type' => 'auto_cashback',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 2,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'অধিক পয়েন্টের পণ্য',
                    'title_en' => 'High Point Products',
                    'slug' => 'high-point',
                    'icon' => 'fas fa-coins',
                    'icon_color' => '#f59e0b',
                    'section_type' => 'auto_point',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 3,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'ফিচার পণ্য',
                    'title_en' => 'Featured Products',
                    'slug' => 'featured-products',
                    'icon' => 'fas fa-bolt',
                    'icon_color' => '#eab308',
                    'section_type' => 'featured',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 4,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'অর্গানিক পণ্য',
                    'title_en' => 'Organic Products',
                    'slug' => 'organic-products',
                    'icon' => 'fas fa-leaf',
                    'icon_color' => '#15803d',
                    'section_type' => 'category',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 5,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'ফ্যাশন ও লাইফস্টাইল',
                    'title_en' => 'Fashion & Lifestyle',
                    'slug' => 'fashion-lifestyle',
                    'icon' => 'fas fa-tshirt',
                    'icon_color' => '#ec4899',
                    'section_type' => 'category',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 6,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'ইলেকট্রনিক্স পণ্য',
                    'title_en' => 'Electronics Products',
                    'slug' => 'electronics',
                    'icon' => 'fas fa-tv',
                    'icon_color' => '#2563eb',
                    'section_type' => 'category',
                    'category_ids' => json_encode([]),
                    'product_limit' => 4,
                    'sort_order' => 7,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title_bn' => 'সকল পণ্য',
                    'title_en' => 'All Products',
                    'slug' => 'all-products',
                    'icon' => 'fas fa-th-large',
                    'icon_color' => '#dc2626',
                    'section_type' => 'all',
                    'category_ids' => json_encode([]),
                    'product_limit' => 10,
                    'sort_order' => 8,
                    'is_visible' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];
            DB::table('home_sections')->insert($defaultSections);
        }

        // 4. Create public_notices table
        if (!Schema::hasTable('public_notices')) {
            Schema::create('public_notices', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('summary')->nullable();
                $table->longText('content')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_published')->default(1);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();

                $table->index(['is_published', 'published_at']);
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
        Schema::dropIfExists('public_notices');
        Schema::dropIfExists('home_sections');
    }
}

