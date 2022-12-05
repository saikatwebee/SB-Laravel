<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutionForwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execution_forward', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('problem_id')->nullable()->index('execution_forward_ibfk_1')->comment('link with problem table');
            $table->integer('customer_id')->nullable()->index('execution_forward_ibfk_2')->comment('link with customer table');
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execution_forward');
    }
}
