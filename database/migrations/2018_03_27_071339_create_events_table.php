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
            $table->longtext('name');
            $table->longtext('description');
            $table->longtext('start_date');
            $table->longtext('end_date');
            $table->longtext('start_time');
            $table->longtext('end_time');
            $table->longtext('u_id');
            $table->longtext('co_id');
            $table->longtext('c_id');
            $table->longtext('f_id');
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
