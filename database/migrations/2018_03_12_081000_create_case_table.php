<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('case', function (Blueprint $table) {
        $table->increments('id');
        $table->text('status');        
        $table->longText('number');
        $table->longText('name');
        $table->longText('description');        
        $table->longText('court_name');
        $table->longText('opposing_councel');
        $table->longText('claim_reference_number');
        $table->longText('location');
        $table->dateTime('open_date');
        $table->dateTime('close_date');
        $table->text('statute_of_limitations');
        $table->text('is_billable');
        $table->text('billing_type');
        $table->text('billing_rate', 4, 2);
        $table->integer('firm_id');
        $table->integer('u_id');
        $table->integer('order_id');
        $table->integer('is_deleted');
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
        Schema::dropIfExists('case');
    }
}
