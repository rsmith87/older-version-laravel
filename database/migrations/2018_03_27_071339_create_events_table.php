<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('name');
            $table->longText('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('approved');
            $table->integer('u_id');
            $table->integer('co_id');
            $table->integer('c_id');
            $table->integer('f_id');
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
        Schema::dropIfExists('events');
    }
}
