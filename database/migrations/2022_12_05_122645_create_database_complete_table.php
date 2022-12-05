<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_complete', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('company', 300)->nullable();
            $table->string('name', 300)->nullable();
            $table->string('designation', 300)->nullable();
            $table->string('address1', 500)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('turnover', 100)->nullable();
            $table->integer('created_by')->nullable()->index('database_complete_ibfk_4')->comment('link with user table');
            $table->integer('customer_id')->nullable()->index('database_complete_ibfk_1')->comment('link with customer table');
            $table->date('last_contacted')->nullable();
            $table->dateTime('assigned_date')->nullable();
            $table->integer('assigned_to')->default(28)->index('database_complete_ibfk_2')->comment('link with user table');
            $table->string('lead_type', 100)->nullable();
            $table->string('priority', 125)->nullable()->comment('auto generated priority from system');
            $table->string('sales_priority')->nullable()->comment('priority has been defined by sales team');
            $table->integer('previously_assigned')->nullable()->index('database_complete_ibfk_3')->comment('link with user table');
            $table->string('skills', 1000)->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('grade', 55)->nullable();
            $table->string('list_name', 50)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->integer('auto_lead')->default(0)->comment('0- not assigned,
1- assigned through lead automation ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_complete');
    }
};
