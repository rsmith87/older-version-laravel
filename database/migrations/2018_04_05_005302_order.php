<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('orders', function (Blueprint $table) {
          $table->increments('id');
          $table->uuid('order_uuid');          
          $table->float('amount');
          $table->float('amount_remaining');
          $table->integer('client_id');
          $table->integer('firm_id');
          $table->uuid('case_uuid');
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
        Schema::dropIfExists('orders');
    }
}
