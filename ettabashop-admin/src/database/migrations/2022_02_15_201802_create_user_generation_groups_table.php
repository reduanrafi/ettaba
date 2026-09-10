<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGenerationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_generation_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('g1')->nullable();
            $table->string('g2')->nullable();
            $table->string('g3')->nullable();
            $table->string('g4')->nullable();
            $table->string('g5')->nullable();
            $table->string('g6')->nullable();
            $table->string('g7')->nullable();
            $table->string('g8')->nullable();
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
        Schema::dropIfExists('user_generation_groups');
    }
}
