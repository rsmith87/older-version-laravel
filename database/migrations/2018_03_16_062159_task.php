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
          $table->uuid('task_uuid');          
          $table->longText('task_name');
          $table->longText('task_description');
          $table->uuid('task_list_uuid');
          $table->integer('contact_client_id');
          $table->integer('assigned');
          $table->integer('user_id');
          $table->dateTime('due');
          $table->datetime('complete')->nullable();
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
        Schema::dropIfExists('task');
    }
}
