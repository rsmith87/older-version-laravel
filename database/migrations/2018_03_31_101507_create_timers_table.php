<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimersTable extends Migration
{
  public function up()
  {
      Schema::create('timers', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->uuid('law_case_id');
          $table->integer('user_id');
          $table->timestamp('started_at');
          $table->timestamp('stopped_at')->default(null)->nullable();
          $table->timestamps();
          $table->softDeletes();
      });
  }

  public function down()
  {
      Schema::dropIfExists('timers');
  }
}