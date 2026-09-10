<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unique_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('upazila_id')->nullable();
            $table->string('name');
            $table->string('referral_code');
            $table->integer('referral_count');
            $table->integer('referral_limit');
            $table->enum('type',['admin','customer','sales_staff','store_administrator','store_owner'])->default('customer');
            $table->enum('customer_type',['buy_only','buy_earn'])->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_new')->default(1);
            $table->boolean('is_blocked')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
