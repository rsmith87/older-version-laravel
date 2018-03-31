<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WysiwygDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
        Schema::create('ck_data', function (Blueprint $table) {
          $table->increments('id');
          $table->longtext('name');
          $table->longtext('data');
          $table->longtext('document_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ck_data');
    }
}
