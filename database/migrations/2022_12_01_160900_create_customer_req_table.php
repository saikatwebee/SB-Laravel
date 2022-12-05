<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerReqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_req', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cid')->nullable()->index('customer_req_ibfk_1')->comment('link with customer table');
            $table->string('industry_type', 250);
            $table->integer('industries')->index('customer_req_ibfk_2')->comment('link with industries table');
            $table->integer('category')->nullable()->index('customer_req_ibfk_3')->comment('link with category table');
            $table->string('requirement', 2500);
            $table->string('desc', 450)->nullable();
            $table->string('budget', 750);
            $table->integer('location')->nullable()->index('customer_req_ibfk_4')->comment('link with states table');
            $table->integer('status')->default(0)->comment('0- payment not completed,
1- Payment has been done through direct flow');
            $table->string('land', 250)->nullable();
            $table->string('start_within', 450)->nullable();
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
        Schema::dropIfExists('customer_req');
    }
}
