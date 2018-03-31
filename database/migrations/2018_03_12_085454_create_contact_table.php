<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
          $table->increments('id');
          $table->longText('prefix');
          $table->longText('first_name');
          $table->longText('last_name');
          $table->longText('company');
          $table->longText('company_title');
          $table->longText('phone');
          $table->longText('email');
          $table->longText('address');
          $table->integer('case_id');
          $table->integer('firm_id');  
          $table->integer('is_client');
          $table->integer('user_id');
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
        Schema::dropIfExists('contact');
    }
}
