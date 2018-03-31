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
        $table->longtext('status');        
        $table->longtext('number');
        $table->longtext('name');
        $table->longtext('description');        
        $table->longtext('court_name');
        $table->longtext('opposing_councel');
        $table->longtext('claim_reference_number');
        $table->longtext('location');
        $table->longtext('created_at');
        $table->text('open_date');
        $table->text('close_date');
        $table->text('statute_of_limitations');
        $table->text('is_billable');
        $table->longtext('billing_type');
        $table->longtext('billing_rate', 4, 2);
        $table->longtext('firm_id');
        $table->longtext('u_id');
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
