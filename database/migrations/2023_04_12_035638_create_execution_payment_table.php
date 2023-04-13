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
        Schema::create('execution_payment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('problem_id')->nullable()->index('execution_payment_ibfk_2')->comment('link with problem table');
            $table->integer('invoice_id')->nullable()->index('execution_payment_ibfk_1')->comment('link with invoice table');
            $table->string('industry', 10)->nullable()->comment('advance with gst');
            $table->string('industry_without_gst', 650)->nullable()->comment('advance without gst');
            $table->integer('tds')->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('industry_file', 1000)->nullable();
            $table->integer('status')->default(0)->comment('1- Advance received,
2- Invoice generated');
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
        Schema::dropIfExists('execution_payment');
    }
};
