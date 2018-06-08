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
          $table->uuid('contlient_uuid');          
          $table->longText('prefix');
          $table->longText('first_name');
          $table->longText('last_name');
          $table->text('relationship');
          $table->longText('company');
          $table->longText('company_title');
          $table->longText('phone');
          $table->longText('email');
          $table->longText('address_1');
          $table->longText('address_2');
          $table->longText('city');
          $table->longText('state');
          $table->longText('zip');
          $table->integer('case_id');
          $table->integer('firm_id');  
          $table->integer('is_client');
          $table->integer('has_login');
          $table->integer('user_id');
          $table->integer('is_deleted');
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
        Schema::dropIfExists('contact');
    }
}
