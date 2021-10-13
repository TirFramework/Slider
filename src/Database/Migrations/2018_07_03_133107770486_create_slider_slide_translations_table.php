<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderSlideTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('slider_slide_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slider_slide_id')->unsigned();
            $table->string('locale');
            $table->string('caption_1')->nullable();
            $table->string('caption_2')->nullable();
            $table->string('caption_3')->nullable();
            $table->string('call_to_action_text')->nullable();
            $table->string('file')->nullable();


            $table->unique(['slider_slide_id', 'locale']);
            $table->foreign('slider_slide_id')->references('id')->on('slider_slides')->onDelete('cascade');
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

        Schema::dropIfExists('slider_slide_translations');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
