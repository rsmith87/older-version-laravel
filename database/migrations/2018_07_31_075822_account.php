<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Account extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('accounts', function (Blueprint $table) {
		    $table->integer('id');
		    $table->integer('firm_id');
		    $table->integer('master_user_id');
		    $table->string('stripe_id')->nullable();
		    $table->string('card_brand')->nullable();
		    $table->string('card_last_four')->nullable();
		    $table->timestamp('trial_ends_at')->nullable();
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
	    Schema::dropIfExists('accounts');
    }
}
