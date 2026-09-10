<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandCashCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hand_cash_categories')) {
            Schema::create('hand_cash_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('image')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('is_active')->default(1);
                $table->boolean('is_deleted')->default(0);
                $table->timestamps();
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
        Schema::dropIfExists('hand_cash_categories');
    }
}
