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
          $table->longtext('prefix');
          $table->longtext('first_name');
          $table->longtext('last_name');
          $table->longtext('company');
          $table->longtext('company_title');
          $table->longtext('phone');
          $table->longtext('email');
          $table->longtext('address');
          $table->longtext('case_id');
          $table->longtext('firm_id');  
          $table->longtext('is_client');
          $table->longtext('user_id');
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
