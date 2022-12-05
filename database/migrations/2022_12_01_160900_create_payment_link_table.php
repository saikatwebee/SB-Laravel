<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_link', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customer_id')->nullable()->index('payment_link_ibfk_1')->comment('link with customer table');
            $table->integer('plan_id')->nullable()->index('payment_link_ibfk_2')->comment('link with subscriberplane  table');
            $table->integer('discount')->nullable();
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
        Schema::dropIfExists('payment_link');
    }
}
