<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToProductHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('product_histories', 'old_quantity')) {
                $table->string('old_quantity')->nullable()->after('old_direct_refer_commission');
            }
            if (!Schema::hasColumn('product_histories', 'old_vat')) {
                $table->string('old_vat')->nullable()->after('old_quantity');
            }
            if (!Schema::hasColumn('product_histories', 'old_tcb')) {
                $table->string('old_tcb')->nullable()->after('old_vat');
            }
            if (!Schema::hasColumn('product_histories', 'old_trp')) {
                $table->string('old_trp')->nullable()->after('old_tcb');
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
        Schema::table('product_histories', function (Blueprint $table) {
            $table->dropColumn(['old_quantity', 'old_vat', 'old_tcb', 'old_trp']);
        });
    }
}
