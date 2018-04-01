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
          $table->longText('task_description');
          $table->integer('f_id');
          $table->integer('c_id');
          $table->integer('t_id');
          $table->integer('contact_client_id');
          $table->integer('assigned');
          $table->dateTime('due');          
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
        Schema::dropIfExists('subtasks');
    }
}
