<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Task extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
        Schema::create('task', function (Blueprint $table) {
          $table->increments('id');
          $table->longText('task_name');
          $table->longText('task_description');
          $table->integer('task_list_id');
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
        Schema::dropIfExists('tasks');
    }
}
