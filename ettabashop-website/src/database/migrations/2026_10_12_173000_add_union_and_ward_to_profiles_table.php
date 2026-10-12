<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnionAndWardToProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'union_name')) {
                $table->string('union_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('profiles', 'ward_no')) {
                $table->string('ward_no')->nullable()->after('union_name');
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
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'ward_no')) {
                $table->dropColumn('ward_no');
            }
            if (Schema::hasColumn('profiles', 'union_name')) {
                $table->dropColumn('union_name');
            }
        });
    }
}
