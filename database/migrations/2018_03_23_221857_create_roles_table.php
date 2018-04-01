<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
  public function up()
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->increments('id');
      $table->longText('name');
      $table->longText('description');
      $table->timestamps();
    });
  }
  
  public function down()
  {
    Schema::dropIfExists('roles');
  }
}
