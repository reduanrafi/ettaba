<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('unique_id');
            $table->string('slug');
            $table->string('name_en');
            $table->string('name_bn');
            $table->text('description_en');
            $table->text('description_bn');
            $table->string('mobile');
            $table->string('address_en');
            $table->string('address_bn');
            $table->string('logo_image');
            $table->string('banner_image')->nullable();
            $table->integer('sales')->nullable();
            $table->integer('reviews')->nullable();
            $table->integer('ratings')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function($table)
        {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
        });
        Schema::dropIfExists('shops');
    }
}
