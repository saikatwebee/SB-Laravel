<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->index('activity_log_ibfk_1')->comment('link with customer table');
            $table->integer('problem_id')->nullable()->index('activity_log_ibfk_2')->comment('link with problem table');
            $table->string('comments')->nullable();
            $table->integer('status')->nullable();
            $table->integer('visibility')->default(0);
            $table->timestamp('date_added')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
