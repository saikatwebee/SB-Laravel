<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinar', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('customer_id')->comment('link with customer table');
            $table->string('name', 500)->nullable();
            $table->string('email', 750)->nullable();
            $table->string('phone', 250)->nullable();
            $table->integer('session_no')->default(0);
            $table->string('sb_current_typ', 500)->nullable();
            $table->string('sb_current_src', 500)->nullable();
            $table->string('sb_current_mdm', 500)->nullable();
            $table->string('sb_current_cmp', 500)->nullable();
            $table->string('sb_current_add_fd', 500)->nullable();
            $table->string('sb_current_add_ep', 500)->nullable();
            $table->string('sb_current_add_rf', 500)->nullable();
            $table->integer('status')->default(0)->comment('0- payment not completed,
1- payment done');
            $table->string('webinar_type', 500)->nullable();
            $table->timestamp('date_added')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinar');
    }
}
