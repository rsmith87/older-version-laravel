<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('lead_uuid');
            $table->text('prefix');
            $table->text('first_name');
            $table->text('last_name');
            $table->integer('converted');
            $table->text('company');
            $table->text('company_title');
            $table->text('phone');
            $table->text('email');
            $table->text('address_1');
            $table->text('address_2');
            $table->text('city');
            $table->text('state');
            $table->text('zip');
            $table->integer('firm_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('leads');
    }
}
