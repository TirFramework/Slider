<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('autoplay')->nullable();
            $table->integer('autoplay_speed')->nullable();
            $table->boolean('arrows')->nullable();
            $table->boolean('dots')->nullable();
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('sliders');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
