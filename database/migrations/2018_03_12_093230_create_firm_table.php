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
          $table->longText('firm_name');
          $table->longText('firm_address');
          $table->longText('firm_phone');
          $table->longText('firm_fax');
          $table->longText('firm_email');
          $table->longText('team');
          $table->longText('social_media');
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
        Schema::dropIfExists('firm');
    }
}
