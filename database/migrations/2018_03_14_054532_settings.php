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
          $table->longText('state_of_bar');
          $table->longText('bar_number');
          $table->longText('practice_areas');
          $table->integer('firm_id');
          $table->text('tz');
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
