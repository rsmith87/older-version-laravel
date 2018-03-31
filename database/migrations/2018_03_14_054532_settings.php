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
          $table->longtext('user_id');
          $table->longtext('theme');
          $table->longtext('table_color');
          $table->longtext('state_of_bar');
          $table->longtext('bar_number');
          $table->longtext('practice_areas');
          $table->longtext('firm_id');
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
