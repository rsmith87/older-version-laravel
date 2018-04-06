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
          $table->longText('name');
          $table->longText('address_1');
          $table->longText('address_2');
          $table->text('city');
          $table->text('state');
          $table->text('zip');
          $table->longText('phone');
          $table->longText('fax');
          $table->longText('email');
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
