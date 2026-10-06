<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 10, 3)->nullable()->after('unit');
            }
            if (!Schema::hasColumn('products', 'weight_unit')) {
                $table->string('weight_unit', 10)->default('gm')->nullable()->after('weight');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'weight_unit')) {
                $table->dropColumn('weight_unit');
            }
            if (Schema::hasColumn('products', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }
};
