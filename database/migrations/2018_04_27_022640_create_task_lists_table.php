<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('task_list_uuid');            
            $table->text('task_list_name'); 
            $table->integer('user_id');
            $table->integer('f_id'); 
            $table->integer('contact_client_id'); 
            $table->integer('c_id');
            $table->integer('assigned'); 
            $table->datetime('due');
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
        Schema::dropIfExists('task_lists');
    }
}
