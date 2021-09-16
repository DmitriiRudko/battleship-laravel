<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warships', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('game_id')->index('fk_warships_games1_idx');
            $table->integer('size');
            $table->integer('number');
            $table->integer('x')->nullable();
            $table->integer('y')->nullable();
            $table->string('orientation', 10)->nullable();
            $table->integer('user_id')->index('fk_warships_users1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warships');
    }
}
