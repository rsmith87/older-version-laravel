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
          $table->uuid('document_uuid');          
          $table->longText('name');
          $table->longText('description');
          $table->longText('location');
          $table->longText('path');
          $table->text('mime_type');
          $table->integer('case_id');
          $table->integer('client_id');
          $table->integer('contact_id');
          $table->integer('firm_id');
          $table->integer('client_share');
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
        Schema::dropIfExists('document');
    }
}
