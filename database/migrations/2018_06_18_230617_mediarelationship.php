<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MediaRelationship extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media_relationship', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('media_uuid');
            $table->text('model');
            $table->integer('model_id');
            $table->integer('user_id');
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('media_relationship');
    }
}
