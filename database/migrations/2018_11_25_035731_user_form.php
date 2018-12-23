<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->text('type');
            $table->text('name');
            $table->longText('data');
            $table->boolean('firm_share');
            $table->integer('user_id');
            $table->integer('case_id');
            $table->integer('firm_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_forms_complete', function(Blueprint $table){
            $table->increments('id');
            $table->uuid('uuid');
            $table->uuid('form_uuid');
            $table->longText('user_data');
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
        Schema::dropIfExists('user_forms');
        Schema::dropIfExists('user_forms_complete');
    }
}
