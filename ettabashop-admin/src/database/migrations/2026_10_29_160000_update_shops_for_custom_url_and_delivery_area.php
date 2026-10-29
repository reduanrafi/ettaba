<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateShopsForCustomUrlAndDeliveryArea extends Migration
{
    public function up()
    {
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (!Schema::hasColumn('shops', 'delivery_area_bn')) {
                    $table->text('delivery_area_bn')->nullable()->after('address_bn');
                }
                if (!Schema::hasColumn('shops', 'delivery_area_en')) {
                    $table->text('delivery_area_en')->nullable()->after('address_en');
                }
            });

            // Normalize existing shop slugs so each shop has a unique URL-safe slug (e.g. Gadgets_Bro)
            $shops = DB::table('shops')->orderBy('id', 'asc')->get();
            $usedSlugs = [];

            foreach ($shops as $shop) {
                $raw = trim($shop->slug ?: $shop->name_en ?: ('Shop_' . $shop->id));
                $slug = preg_replace('/\s+/', '_', $raw);
                $slug = preg_replace('/[^A-Za-z0-9_\-\x{0980}-\x{09FF}]/u', '', $slug);
                if ($slug === '') {
                    $slug = 'Seller_' . ($shop->unique_id ?: $shop->id);
                }

                $baseSlug = $slug;
                $counter = 1;
                while (in_array(strtolower($slug), $usedSlugs, true)) {
                    $slug = $baseSlug . '_' . $counter;
                    $counter++;
                }

                $usedSlugs[] = strtolower($slug);

                if ($shop->slug !== $slug) {
                    DB::table('shops')->where('id', $shop->id)->update(['slug' => $slug]);
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (Schema::hasColumn('shops', 'delivery_area_bn')) {
                    $table->dropColumn('delivery_area_bn');
                }
                if (Schema::hasColumn('shops', 'delivery_area_en')) {
                    $table->dropColumn('delivery_area_en');
                }
            });
        }
    }
}

