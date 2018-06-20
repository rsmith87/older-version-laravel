<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mediashare extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media_share', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('media_uuid');
            $table->integer('user_id');
            $table->integer('user_id_share_with');
            $table->integer('firm_id_share_with');            
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('media_share');
    }
}
