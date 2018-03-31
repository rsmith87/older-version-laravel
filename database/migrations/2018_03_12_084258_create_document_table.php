<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
          $table->increments('id');
          $table->longtext('name');
          $table->longtext('description');
          $table->longtext('location');
          $table->longtext('path');
          $table->longtext('case_id');
          $table->longtext('client_id');
          $table->longtext('contact_id');
          $table->longtext('firm_id');
          $table->longtext('user_id');
          $table>timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document');
    }
}
