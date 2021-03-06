<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtasksTable extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
        Schema::create('subtask', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->longText('subtask_name');
          $table->longText('subtask_description');
          $table->integer('t_id');
          $table->integer('assigned');
          $table->datetime('complete')->nullable();
          $table->dateTime('due');          
          $table->timestamps();
          $table->softDeletes();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtask');
    }
}
