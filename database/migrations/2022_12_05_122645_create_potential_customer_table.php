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
        Schema::create('potential_customer', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('company_id')->nullable()->index('potential_customer_ibfk_1')->comment('link with database_complete table');
            $table->integer('created_by')->nullable()->index('potential_customer_ibfk_2')->comment('link with user table');
            $table->string('user_name', 200)->nullable();
            $table->integer('nextweek_no')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->date('created')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potential_customer');
    }
};
