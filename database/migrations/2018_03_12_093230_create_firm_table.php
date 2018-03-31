<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firm', function (Blueprint $table) {
          $table->increments('id');
          $table->longtext('firm_name');
          $table->longtext('firm_address');
          $table->longtext('firm_phone');
          $table->longtext('firm_fax');
          $table->longtext('firm_email');
          $table->longtext('team');
          $table->longtext('social_media');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm');
    }
}
