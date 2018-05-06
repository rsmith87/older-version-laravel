<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->text('theme');
          $table->text('table_color');
          $table->text('table_size');
          $table->text('education');
          $table->text('experience');
          $table->text('location');
          $table->text('focus');
          $table->text('title');
          $table->text('profile_image');
          $table->longText('state_of_bar');
          $table->longText('bar_number');
          $table->longText('practice_areas');
          $table->integer('firm_id');
          $table->text('tz');
          $table->text('fb');
          $table->text('twitter');
          $table->text('instagram');
          $table->text('avvo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
