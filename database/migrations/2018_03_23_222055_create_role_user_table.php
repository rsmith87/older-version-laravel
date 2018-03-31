<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
  public function up()
  {
    Schema::create('role_user', function (Blueprint $table) {
      $table->increments('id');
      $table->longtext('role_id');
      $table->longtext('user_id');
    });
  }
  public function down()
  {
    Schema::dropIfExists('role_user');
  }
}
